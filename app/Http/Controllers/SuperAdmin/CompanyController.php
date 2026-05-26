<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    const MODULES = [
        'sellers' => 'Asesores Comerciales',
        'contracts' => 'Contratos',
        'cobranzas' => 'Cobranzas',
        'egresos' => 'Egresos',
        'caja_y_cuentas' => 'Caja y cuentas',
        'traslados' => 'Traslados',
        'metas' => 'Metas',
    ];

    public function index()
    {
        $companies = Company::all();
        $modules = self::MODULES;
        return view('superadmin.companies.index', compact('companies', 'modules'));
    }

    public function create()
    {
        $modules = self::MODULES;
        return view('superadmin.companies.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'registry_info' => 'nullable|string|max:255',
            'insurance_amount' => 'required|numeric|min:0',
            'number_pagare' => 'required|integer|min:0',
            'logo' => 'nullable|image|max:2048',
            'permissions' => 'nullable|array',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/logos'), $filename);
            $data['logo'] = 'assets/images/logos/' . $filename;
        } else {
            $data['logo'] = 'assets/images/logo.png';
        }

        $data['permissions'] = $request->input('permissions', []);
        $data['status'] = 1;

        Company::create($data);

        return redirect()->route('superadmin.companies.index')->with('success', 'Financiera creada con éxito.');
    }

    public function edit(Company $company)
    {
        $modules = self::MODULES;
        return view('superadmin.companies.edit', compact('company', 'modules'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'registry_info' => 'nullable|string|max:255',
            'insurance_amount' => 'required|numeric|min:0',
            'number_pagare' => 'required|integer|min:0',
            'logo' => 'nullable|image|max:2048',
            'permissions' => 'nullable|array',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/logos'), $filename);
            $data['logo'] = 'assets/images/logos/' . $filename;
        }

        $data['permissions'] = $request->input('permissions', []);

        $company->update($data);

        return redirect()->route('superadmin.companies.index')->with('success', 'Financiera actualizada con éxito.');
    }

    public function togglePermission(Request $request, Company $company)
    {
        $module = $request->input('module');
        if (!array_key_exists($module, self::MODULES)) {
            return response()->json(['error' => 'Módulo inválido.'], 400);
        }

        $permissions = is_array($company->permissions) ? $company->permissions : [];
        if (in_array($module, $permissions)) {
            $permissions = array_diff($permissions, [$module]);
        } else {
            $permissions[] = $module;
        }

        $company->permissions = array_values($permissions);
        $company->save();

        return response()->json(['success' => true, 'permissions' => $company->permissions]);
    }

    public function toggleStatus(Company $company)
    {
        $company->status = $company->status === 1 ? 0 : 1;
        $company->save();

        return redirect()->route('superadmin.companies.index')->with('success', 'Estado de la financiera actualizado.');
    }
}
