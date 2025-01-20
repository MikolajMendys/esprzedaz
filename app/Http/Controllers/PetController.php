<?php

namespace App\Http\Controllers;

use App\Services\PetService;
use Illuminate\Http\Request;

class PetController extends Controller
{
    private PetService $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function index(Request $request)
    {
        $status = $request->input('status', 'available');
        $page = $request->input('page', 1);
        $pets = $this->petService->getPetsByStatus($status, $page, 10);
        $pets->withPath('/pets');
        return view('pets.index', compact('pets', 'status'));
    }

    public function show($id)
    {
        try {
            $pet = $this->petService->getPet($id);
            return view('pets.show', compact('pet'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('pets.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'status' => 'required|in:available,pending,sold',
                'category.name' => 'nullable|string',
                'tags' => 'nullable|array',
                'tags.*' => 'nullable|string',
            ]);
    
            $petData = [
                'name' => $validated['name'],
                'status' => $validated['status'],
                'category' => [
                    'name' => $validated['category']['name'] ?? null,
                ],
                'tags' => array_filter(array_map(function ($tag, $index) {
                    return $tag ? ['id' => $index, 'name' => $tag] : null;
                }, $validated['tags'] ?? [], array_keys($validated['tags'] ?? []))),
            ];
    
            $pet = $this->petService->createPet($petData);
            return redirect()->route('pets.edit', $pet->id)->with('success', 'Pet created successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $pet = $this->petService->getPet($id);
            return view('pets.edit', compact('pet'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'status' => 'required|in:available,pending,sold',
                'photoUrls' => 'string|nullable',
                'category.name' => 'nullable|string',
                'tags' => 'nullable|array',
                'tags.*' => 'nullable|string',
            ]);

            $petData = [
                'id' => $id,
                'name' => $validated['name'],
                'status' => $validated['status'],
                'category' => [
                    'id' => 0,
                    'name' => $validated['category']['name'] ?? null,
                ],
                'photoUrls' => [$validated['photoUrls']] ?? [],
                'tags' => array_filter(array_map(function ($tag, $index) {
                    return $tag ? ['id' => $index, 'name' => $tag] : null;
                }, $validated['tags'] ?? [], array_keys($validated['tags'] ?? []))),
            ];

            $pet = $this->petService->updatePet($petData);

            return redirect()->route('pets.edit', $pet->id)->with('success', 'Pet updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->petService->deletePet($id);

            return redirect()->route('pets.index')->with('success', 'Pet deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function uploadImage(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'photo' => 'required|file|image',
                'additionalMetadata' => 'nullable|string',
            ]);
    
            $image = $validated['photo'];
            $additionalMetadata = $request->input('additionalMetadata', '');
            $url = $this->petService->uploadImageToExternalServer($id, $image, $additionalMetadata);
    
            return response()->json(['success' => true, 'url' => $url]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
