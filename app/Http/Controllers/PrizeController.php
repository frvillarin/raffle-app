<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PrizeController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Prizes/Index', [
            'prizes' => Prize::with('winner.participant')->paginate(25)
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Prizes/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            Prize::create([
                'title' => $request->title,
            ]);
            DB::commit();
            return redirect()->route('admin.prizes.index')->withMessage('Prize has been created!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['errors' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Prize $prize
     * @return \Illuminate\Http\Response
     */
    public function show(Prize $prize)
    {
        //
    }


    public function edit(Prize $prize)
    {
        return Inertia::render('Admin/Prizes/Edit', [
            'prize' => $prize
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Prize $prize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prize $prize)
    {
        $request->validate([
            'title' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            $prize->update([
                'title' => $request->title,
            ]);
            DB::commit();
            return redirect()->route('admin.prizes.index')->withMessage('Prize has been updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['errors' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Prize $prize
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prize $prize)
    {
        $prize->delete();

        return redirect()->route('admin.prizes.index')->withMessage('Participant has been deleted!');
    }

    public function drawRaffle($prizeId)
    {
        $prize = Prize::where('id', $prizeId)->with('winner.participant')->first();
        return Inertia::render('Admin/Prizes/Draw', [
            'participants' => Participant::all(),
            'prize' => $prize,
        ]);
    }
}
