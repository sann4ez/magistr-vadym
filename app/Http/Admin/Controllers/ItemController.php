<?php

namespace App\Http\Admin\Controllers;

use App\Exports\ItemsExport;
use App\Http\Admin\Requests\ItemRequest;
use App\Imports\ItemsImport;
use App\Models\Item;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

final class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with('translation')->orderBy('key')->get();

        return view('admin.items.index', compact('items'));
    }

    public function create(Request $request)
    {
        return response()->json([
            'html' => view('admin.items.inc.create', ['type' => $request->type])
                ->render()
        ]);
    }

    public function store(ItemRequest $request)
    {
        $item = Item::create($request->getData());

        $item->mediaManage($request);

        return redirect()->back()
            ->with('success', trans('alerts.store.success'));
    }


    public function edit(Request $request, Item $item)
    {
        return response()->json([
            'html' => view('admin.items.inc.edit', [
                'itemUpdate' => $item,
                'type' => $request->type,
            ])->render(),
        ]);
    }


    public function update(ItemRequest $request, Item $item)
    {
        $item->update($request->getData());

        $item->mediaManage($request);

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }

    public function order(Request $request)
    {
        $this->validate($request, [
            'data' => 'required|array'
        ]);

        foreach ($request->data as $weight => $id) {
            Item::find($id)?->update(['weight' => $weight]);
        }

        return response()
            ->json(['message' => trans('alerts.update.success')]);
    }

    public function destroy(Item $item)
    {
        if ($item->is_guarded) {
            return redirect()->back()
                ->with('error', trans('alerts.destroy.error'));
        }

        $item->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    public function editable(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'nullable|string',
        ]);

        if (in_array($request->name, ['key'])) {
            return response()
                ->json(['message' => trans('alerts.operation.error'), 'status' => 'error']);
        }

        $item->setAttribute($request->name, $request->value);
        $item->save();

        if (!$request->ajax()) {
            return \redirect()
                ->back()->with('success', 'Дані успішно збережено!');
        }

        return response()
            ->json(['message' => 'Дані успішно збережено!']);
    }
}
