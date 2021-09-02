<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Rap2hpoutre\FastExcel\FastExcel;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admin/Participants/List', [
            'participants' => Participant::paginate(25)
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Participants/Create');
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
            'name' => ['required'],
            'department' => ['required'],
            'shift' => ['required']
        ]);
        try {
            DB::beginTransaction();
            Participant::create([
                'name' => $request->name,
                'department' => $request->department,
                'shift' => $request->shift,
            ]);
            DB::commit();
            return redirect()->route('admin.participants.index')->withMessage('Participant has been added!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['errors' => $e->getMessage()]);
        }
    }


    public function importData(Request $request)
    {
        $request->validate([
           'file' => ['mimes:xlsx']
        ]);
        try {
            DB::beginTransaction();

            $collection = (new FastExcel)->import($request->file('file'));

            $participants = (new FastExcel)->import($request->file('file'), function ($line) {
                return Participant::create([
                    'name' => $line['NAMES'],
                    'department' => $line['DEPARTMENT'],
                    'shift' => $line['SHIFT']
                ]);
            });

            DB::commit();
            return redirect()->back()->withMessage('Import Success!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withMessage($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Participant $participant
     * @return \Illuminate\Http\Response
     */
    public function show(Participant $participant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Participant $participant
     * @return \Illuminate\Http\Response
     */
    public function edit(Participant $participant)
    {
        return Inertia::render('Admin/Participants/Edit', [
            'participant' => $participant
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Participant $participant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Participant $participant)
    {
        $request->validate([
            'name' => ['required'],
            'department' => ['required'],
            'shift' => ['required']
        ]);

        try {
            DB::beginTransaction();
           $participant->update([
                'name' => $request->name,
                'department' => $request->department,
                'shift' => $request->shift,
            ]);
            DB::commit();
            return redirect()->route('admin.participants.index')->withMessage('Participant has been updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['errors' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Participant $participant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('admin.participants.index')->withMessage('Participant has been deleted!');
    }
}
