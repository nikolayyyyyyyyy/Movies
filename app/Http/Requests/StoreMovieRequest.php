<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'iframe_url' => 'required|url',
            'rating' => 'nullable|numeric|min:0|max:10',
            'year' => 'nullable|numeric|min:1800',
            'duration' => 'required',
            'categories' => 'required|array',
            'actors' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required',
            'title.max' => 'The title must be less than 255 characters',
            'description.max' => 'The description must be less than 500 characters',
            'image.image' => 'The image must be an image',
            'image.mimes' => 'The image must be a jpeg, png, jpg, gif, or svg',
            'image.max' => 'The image must be less than 2048 kilobytes',
            'iframe_url.required' => 'The iframe url is required',
            'iframe_url.url' => 'The iframe url must be a valid url',
            'rating.numeric' => 'The rating must be a number',
            'rating.min' => 'The rating must be greater than 0',
            'rating.max' => 'The rating must be less than 10',
            'year.numeric' => 'The year must be a number',
            'year.min' => 'The year must be greater than 1900',
            'duration.required' => 'The duration is required',
        ];
    }
}
