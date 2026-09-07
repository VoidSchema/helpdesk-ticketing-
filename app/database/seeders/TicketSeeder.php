<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::where('role', Role::Agent)->get();
        $users = User::where('role', Role::User)->get();
        $categories = \App\Models\Category::all();

        $tickets = [
            [
                'title' => 'Cannot connect to office WiFi',
                'description' => 'My laptop keeps disconnecting from the office WiFi every few minutes. I have tried restarting the laptop and forgetting the network, but the issue persists. This is affecting my ability to work.',
                'status' => 'open',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Network')->first()->id,
                'created_by' => $users[0]->id,
                'assigned_to' => $agents[0]->id,
            ],
            [
                'title' => 'Request for dual monitor setup',
                'description' => 'I would like to request a second monitor for my workstation. I work with multiple spreadsheets and documents simultaneously, and having dual monitors would significantly improve my productivity.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'New Request')->first()->id,
                'created_by' => $users[1]->id,
                'assigned_to' => $agents[1]->id,
            ],
            [
                'title' => 'Outlook keeps crashing when opening attachments',
                'description' => 'Every time I try to open a PDF attachment in Outlook, the application crashes. I have tried repairing Office and reinstalling Outlook, but the problem continues. I need to access important documents for a client meeting.',
                'status' => 'open',
                'priority' => 'urgent',
                'category_id' => $categories->where('name', 'Software')->first()->id,
                'created_by' => $users[2]->id,
                'assigned_to' => null,
            ],
            [
                'title' => 'Password reset for domain account',
                'description' => 'I was locked out of my domain account after entering the wrong password multiple times. I need a password reset to regain access to my email and files.',
                'status' => 'resolved',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Account Access')->first()->id,
                'created_by' => $users[3]->id,
                'assigned_to' => $agents[0]->id,
            ],
            [
                'title' => 'Printer on 3rd floor not working',
                'description' => 'The HP LaserJet printer on the 3rd floor near the break room is showing an error message and not printing. Multiple people in our department use this printer.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Hardware')->first()->id,
                'created_by' => $users[0]->id,
                'assigned_to' => $agents[1]->id,
            ],
            [
                'title' => 'VPN connection timeout when working remotely',
                'description' => 'When I try to connect to the company VPN from home, it times out after about 30 seconds. I need VPN access to work on sensitive client data. My home internet connection is stable.',
                'status' => 'open',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Network')->first()->id,
                'created_by' => $users[4]->id,
                'assigned_to' => null,
            ],
            [
                'title' => 'Request for Adobe Creative Suite license',
                'description' => 'I need Adobe Creative Suite (Photoshop, Illustrator, InDesign) for my role in the marketing team. My current trial is expiring soon. Please let me know if there are any forms I need to fill out.',
                'status' => 'open',
                'priority' => 'low',
                'category_id' => $categories->where('name', 'Software')->first()->id,
                'created_by' => $users[1]->id,
                'assigned_to' => $agents[0]->id,
            ],
            [
                'title' => 'Suspicious email received - possible phishing',
                'description' => 'I received an email that appears to be from our CEO asking me to purchase gift cards urgently. The email address looks slightly different from the real one. I have not clicked any links or replied.',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'category_id' => $categories->where('name', 'Security')->first()->id,
                'created_by' => $users[2]->id,
                'assigned_to' => $agents[1]->id,
            ],
            [
                'title' => 'Laptop running very slow',
                'description' => 'My laptop has been extremely slow for the past week. Applications take a long time to open, and switching between windows causes freezes. I have already run a disk cleanup.',
                'status' => 'open',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Hardware')->first()->id,
                'created_by' => $users[3]->id,
                'assigned_to' => null,
            ],
            [
                'title' => 'Calendar not syncing with phone',
                'description' => 'My Outlook calendar is not syncing with my iPhone anymore. New meetings added on my laptop do not appear on my phone, and vice versa. This started after the last iOS update.',
                'status' => 'open',
                'priority' => 'low',
                'category_id' => $categories->where('name', 'Email')->first()->id,
                'created_by' => $users[4]->id,
                'assigned_to' => $agents[0]->id,
            ],
            [
                'title' => 'New employee onboarding - Day 1 setup',
                'description' => 'We have a new employee starting next Monday. They need a workstation set up with standard software, email account, and access to shared drives. Their name is Alex Johnson, department: Sales.',
                'status' => 'open',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'New Request')->first()->id,
                'created_by' => $users[0]->id,
                'assigned_to' => $agents[1]->id,
            ],
            [
                'title' => 'Projector in Conference Room B not displaying',
                'description' => 'The ceiling projector in Conference Room B is not displaying anything from connected laptops. The projector turns on but shows a blue screen. We have an important client presentation tomorrow.',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'category_id' => $categories->where('name', 'Hardware')->first()->id,
                'created_by' => $users[1]->id,
                'assigned_to' => $agents[0]->id,
            ],
            [
                'title' => 'Access to shared drive for Finance department',
                'description' => 'I need read-only access to the Finance shared drive (\\\\fileserver\\Finance) for my audit work. My manager has approved this access. My employee ID is EMP-1234.',
                'status' => 'resolved',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Account Access')->first()->id,
                'created_by' => $users[2]->id,
                'assigned_to' => $agents[1]->id,
            ],
            [
                'title' => 'Slack notifications not appearing on desktop',
                'description' => 'Slack desktop app on Windows is not showing notifications. I miss important messages from my team. The notifications work fine on my phone app.',
                'status' => 'open',
                'priority' => 'low',
                'category_id' => $categories->where('name', 'Software')->first()->id,
                'created_by' => $users[3]->id,
                'assigned_to' => null,
            ],
            [
                'title' => 'Blue screen error on workstation',
                'description' => 'My desktop computer keeps showing a blue screen error (BSOD) with the error code IRQL_NOT_LESS_OR_EQUAL. It happens randomly, sometimes 3-4 times a day. I cannot work reliably.',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'category_id' => $categories->where('name', 'Hardware')->first()->id,
                'created_by' => $users[4]->id,
                'assigned_to' => $agents[0]->id,
            ],
            [
                'title' => 'Request for laptop upgrade',
                'description' => 'My current laptop is 4 years old and struggles with running multiple applications. I frequently use Excel with large datasets, PowerPoint, and browser with many tabs. Can I get an upgrade?',
                'status' => 'open',
                'priority' => 'low',
                'category_id' => $categories->where('name', 'New Request')->first()->id,
                'created_by' => $users[0]->id,
                'assigned_to' => null,
            ],
            [
                'title' => 'Email delays - messages arriving late',
                'description' => 'I have been noticing that emails are arriving 30-60 minutes late. This is causing issues with time-sensitive communications. The problem started yesterday.',
                'status' => 'open',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Email')->first()->id,
                'created_by' => $users[1]->id,
                'assigned_to' => $agents[1]->id,
            ],
            [
                'title' => 'Security badge not working at main entrance',
                'description' => 'My security badge stopped working at the main entrance this morning. I had to use the visitor entrance. I need access restored as soon as possible.',
                'status' => 'resolved',
                'priority' => 'high',
                'category_id' => $categories->where('name', 'Security')->first()->id,
                'created_by' => $users[2]->id,
                'assigned_to' => $agents[0]->id,
            ],
            [
                'title' => 'Need admin rights for software installation',
                'description' => 'I need temporary admin rights to install a development tool required for a project deadline next week. The tool is Visual Studio Code with specific extensions.',
                'status' => 'resolved',
                'priority' => 'medium',
                'category_id' => $categories->where('name', 'Account Access')->first()->id,
                'created_by' => $users[3]->id,
                'assigned_to' => $agents[1]->id,
            ],
            [
                'title' => 'Keyboard keys sticking',
                'description' => 'Several keys on my keyboard are sticking and require multiple presses to register. This is slowing down my typing significantly. The keyboard is a standard Dell USB keyboard.',
                'status' => 'open',
                'priority' => 'low',
                'category_id' => $categories->where('name', 'Hardware')->first()->id,
                'created_by' => $users[4]->id,
                'assigned_to' => null,
            ],
        ];

        foreach ($tickets as $ticketData) {
            $ticket = Ticket::create($ticketData);

            // Add some comments to tickets
            if ($ticket->status !== 'open' || $ticket->assigned_to) {
                Comment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $ticket->assigned_to ?? $agents->random()->id,
                    'body' => 'I am looking into this issue. Will update you shortly.',
                    'is_internal' => false,
                ]);
            }

            if ($ticket->status === 'resolved') {
                Comment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $ticket->assigned_to,
                    'body' => 'This issue has been resolved. Please let me know if you need further assistance.',
                    'is_internal' => false,
                ]);
            }
        }
    }
}
