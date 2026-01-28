<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Models\LiquidationTransaction;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Redirect;

class AccountsManager extends Controller
{
    public function create(): Response
    {
        return Inertia::render('TestAccounts/AddAccount', [
            'accounts' => Account::where('is_deactivated', false)
            ->select('id', 'account_name')->get(),
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        if ($request->filled('parent_id')) {
            $parent = Account::findOrFail($request->parent_id);
            
            if ($parent->account_level >= 5) {
                return back()->withErrors(['parent_id' => 'Maximum account depth reached.']);
            }

            $level = $parent->account_level + 1;
        }

        Account::create([
            'account_name' => $validated['account_name'],
            'parent_id' => $validated['parent_id'],
            'account_level' => $level,
            'is_deactivated' => false,
        ]);

        return Redirect::route('dashboard')->with('message', 'Account created successfully!');
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        $request->validate([
            'account_name' => 'required|string|max:255|unique:accounts,account_name,' . $account->id,
            'parent_id' => 'nullable|exists:accounts,id',
            'is_deactivated' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $account) {
                
                // 1. Detect Status Change for Deactivation
                $isBeingDeactivated = !$account->is_deactivated && $request->boolean('is_deactivated');

                if ($isBeingDeactivated) {
                    // If this account is deactivated, all children/descendants must be too
                    $descendantIds = $this->getDescendantIds($account);
                    Account::whereIn('id', $descendantIds)->update(['is_deactivated' => true]);
                }

                // 2. Handle Parent/Hierarchy Changes
                if ($request->parent_id != $account->parent_id) {
                    $descendantIds = $descendantIds ?? $this->getDescendantIds($account);
                    
                    if (in_array($request->parent_id, $descendantIds)) {
                        throw new \Exception('An account cannot be moved inside itself or its children.');
                    }

                    $hasTransactions = LiquidationTransaction::whereIn('account_id', $descendantIds)->exists();
                    if ($hasTransactions) {
                        throw new \Exception('Movement restricted: transactions exist for this account branch.');
                    }

                    $newLevel = $request->parent_id 
                        ? Account::find($request->parent_id)->account_level + 1 
                        : 1;

                    $account->account_level = $newLevel;
                    $account->parent_id = $request->parent_id;

                    $this->updateDescendantLevels($account);
                }

                // 3. Save Final State
                $account->account_name = $request->account_name;
                $account->is_deactivated = $request->boolean('is_deactivated');
                $account->save();
            });

            return Redirect::route('dashboard')->with('message', 'Account updated successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['parent_id' => $e->getMessage()]);
        }
    }

    protected function getDescendantIds($account): array
    {
        $ids = [$account->id];
        foreach ($account->children as $child) {
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }

    protected function updateDescendantLevels(Account $parent)
    {
        foreach ($parent->children as $child) {
            $child->account_level = $parent->account_level + 1;
            $child->save();
            
            $this->updateDescendantLevels($child);
        }
    }

    protected function cascadeDeactivation(Account $parent)
    {
        foreach ($parent->children as $child) {
            $child->is_deactivated = true;
            $child->save();

            // Keep going down the tree
            $this->cascadeDeactivation($child);
        }
    }

    public function edit(Account $account)
    {
        $familyIds = $this->getDescendantIds($account);
        
        $hasTransactions = LiquidationTransaction::whereIn('account_id', $familyIds)->exists();

        return Inertia::render('TestAccounts/EditAccount', [
            'account' => $account,
            'accounts' => Account::select('id', 'account_name')->where('id', '!=', $account->id)->get(),
            'hasTransactions' => $hasTransactions
        ]);
    }
}
