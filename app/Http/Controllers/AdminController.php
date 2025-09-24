<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Contract\Database;

class AdminController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // Simple authentication (in production, use proper authentication)
        if ($username === 'admin' && $password === 'rundev2024') {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['credentials' => 'Invalid credentials']);
    }

    public function dashboard()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            // Get all participants from Firebase
            $participantsRef = $this->database->getReference('participants');
            $snapshot = $participantsRef->getSnapshot();
            $participants = $snapshot->getValue() ?: [];

            // Calculate statistics
            $stats = $this->calculateStats($participants);

            return view('admin.dashboard', compact('participants', 'stats'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to load data: ' . $e->getMessage()]);
        }
    }

    public function updateParticipant(Request $request, $id)
    {
        if (!Session::get('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $participantRef = $this->database->getReference('participants/' . $id);
            $participantRef->update($request->all());

            return response()->json(['success' => true, 'message' => 'Participant updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update: ' . $e->getMessage()]);
        }
    }

    public function deleteParticipant($id)
    {
        if (!Session::get('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $participantRef = $this->database->getReference('participants/' . $id);
            $participantRef->remove();

            return response()->json(['success' => true, 'message' => 'Participant deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete: ' . $e->getMessage()]);
        }
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    private function calculateStats($participants)
    {
        $stats = [
            'total' => count($participants),
            'categories' => ['5K' => 0, '10K' => 0, 'Half Marathon' => 0],
            'payment_status' => ['belum' => 0, 'lunas' => 0],
            'shirt_sizes' => ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0],
            'recent_registrations' => []
        ];

        foreach ($participants as $id => $participant) {
            // Count categories
            if (isset($participant['kategoriLomba'])) {
                $stats['categories'][$participant['kategoriLomba']]++;
            }

            // Count payment status
            if (isset($participant['pembayaran'])) {
                $stats['payment_status'][$participant['pembayaran']]++;
            }

            // Count shirt sizes
            if (isset($participant['ukuranBaju'])) {
                $stats['shirt_sizes'][$participant['ukuranBaju']]++;
            }

            // Collect recent registrations
            $participant['firebase_id'] = $id;
            $stats['recent_registrations'][] = $participant;
        }

        // Sort recent registrations by date
        usort($stats['recent_registrations'], function($a, $b) {
            return strtotime($b['registrationDate']) - strtotime($a['registrationDate']);
        });

        // Keep only last 5
        $stats['recent_registrations'] = array_slice($stats['recent_registrations'], 0, 5);

        return $stats;
    }
}
