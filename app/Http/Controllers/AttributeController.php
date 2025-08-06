<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValues;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::all();
        $attributeValues = AttributeValues::with('attribute')->get();

        if ($attributes->isEmpty()) {
            $attributes = collect();
        }

        return view('admin.attributes.attributes', compact('attributes', 'attributeValues'));
    }
    public function storeAttribute(Request $request)
    {
        // Validate input
        $request->validate([
            'attribute_name' => 'required|string|max:255|unique:attributes,name',
            'status' => 'nullable|boolean',
        ]);

        try {

            Attribute::create([
                'name' => $request->attribute_name,
                'status' => $request->status ?? 1,
            ]);

            return redirect()->route('admin.attributes.index')->with('success_message', 'Attribute created successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to create attribute. Please try again.');
        }

        // Redirect with success message
    }
    public function updateAttribute(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);

        $request->validate([
            'attribute_name' => 'required|string|max:255|unique:attributes,name,' . $id,
            'status' => 'nullable|boolean',
        ]);

        $attribute->update([
            'name' => $request->attribute_name,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.attributes.index')->with('success_message', 'Attribute updated successfully!');
    } // end Method

   public function DeleteAttribute(Request $request, $id): JsonResponse
{
    try {
        $attribute = Attribute::with('attributeValues')->findOrFail($id);

        // Agar values hain
        if ($attribute->attributeValues && $attribute->attributeValues->count() > 0) {
            $valueCount = $attribute->attributeValues->count();
            $names = $attribute->attributeValues->pluck('value')->take(5); // pehle 5 dikhao agar zyada hain
            $list = $names->join(', ');
            $more = $valueCount > 5 ? ' and ' . ($valueCount - 5) . ' more' : '';

            $message = "⚠️ Attribute '{$attribute->name}' has {$valueCount} associated values: {$list}{$more}.\n"
                     . "Deleting it will permanently remove all these values.\n"
                     . "This action cannot be undone.";

            // Agar force delete ho raha hai
            if ($request->has('force')) {
                $attribute->attributeValues()->delete(); // Sab values delete
                $attribute->delete(); // Attribute delete
                return response()->json([
                    'status' => 'success',
                    'message' => "Attribute '{$attribute->name}' and its {$valueCount} values deleted permanently!"
                ]);
            }

            // Nahi to confirm karo
            return response()->json([
                'status' => 'confirm',
                'message' => $message,
                'value_count' => $valueCount,
                'attribute_name' => $attribute->name
            ]);
        }

        // Agar koi value nahi hai
        $attributeName = $attribute->name;
        $attribute->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Attribute '{$attributeName}' deleted successfully!"
        ]);

    } catch (\Exception $e) {
        // \Log::error('Error deleting attribute: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
    public function storeAttributeValue(Request $request)
    {
        // Validate input
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'values' => 'required|array|min:1',
            // 'values.*' => 'required|string|max:255|unique:attribute_values,value,NULL,id,attribute_id,' . $request->attribute_id,
        ]);

        try {
            $attributeId = $request->attribute_id;
            $created = 0;

            foreach ($request->values as $value) {
                if (!empty($value)) {
                    AttributeValues::create([
                        'attribute_id' => $attributeId,
                        'value' => trim($value),
                        'status' => 1, // default active
                    ]);
                    $created++;
                }
            }

            return redirect()->back()->with('success_message', "$created value(s) added successfully!");
        } catch (\Exception $ex) {
            // Log::error('Failed to save attribute values: ' . $ex->getMessage());
            return redirect()->back()->withInput()->with('error_message', 'Failed to save values. Please try again.');
        }
    }
}
