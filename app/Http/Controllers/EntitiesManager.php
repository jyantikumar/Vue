<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Required for the unique check
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class EntitiesManager extends Controller
{
    public function create()
    {
        $accounts = Account::filtered('leaves')
            ->with('parent')
            ->get();

        return Inertia::render('TestAccounts/AddEntity', [
            'accounts' => $accounts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'entity_type' => 'required|string',
            'identifier' => [
                'required',
                'string',
                Rule::unique('entities')->where(fn ($query) => 
                    $query->where('entity_type', $request->entity_type)
                ),
            ],
            'status' => 'required|string',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'account_ids' => 'required|array|min:1', 
            'account_ids.*' => 'exists:accounts,id',
        ]);

        DB::transaction(function () use ($validated) {
            // Filter only the entity data for creation
            $entity = Entity::create(collect($validated)->except('account_ids')->toArray());

            // Sync the pivot table
            $entity->accounts()->sync($validated['account_ids']);
        });

        return redirect()->route('dashboard')->with('message', 'Entity created successfully!');
    }
}