<?php

namespace App\Http\Requests;

use App\Models\Movie;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMovieRequest extends FormRequest
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
        /** @var Movie|null $movie */
        $movie = $this->route('movie');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', Rule::unique('movies', 'slug')->ignore($movie?->id)],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'iframe_url' => ['required', 'url'],
            'year' => ['nullable', 'integer', 'min:1800'],
            'duration' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'actors' => ['nullable', 'array'],
            'actors.*' => ['integer', 'exists:actors,id'],
        ];
    }
}
