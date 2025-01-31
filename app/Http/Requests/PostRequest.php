<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ensure only authenticated users can access this
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id', // Validate each tag ID
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Customize error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'content.required' => 'The content field is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
            'post_image.image' => 'The file must be an image.',
            'post_image.mimes' => 'Allowed image formats: jpeg, png, jpg, gif, svg.',
            'post_image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}
