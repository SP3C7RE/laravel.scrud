<?php
/**
 * Created by PhpStorm.
 * User: specter
 * Date: 14.02.18
 * Time: 14:02
 */
namespace App\Mailers;
use App\Ticket;
use Illuminate\Contracts\Mail\Mailer;

class AppMailer
{
    protected $mailer;
    protected $formAddress = 'RNCFDs@gmail.com';
    protected $formName = 'Support ticket';
    protected $to;
    protected $subject;
    protected $view;
    protected $data = [];

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendTicketComments($ticketOwner, $user, Ticket $ticket, $comment)
    {

        $this->to = $ticketOwner -> email;
        $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $this->view = 'emails.ticket_comments';
        $this->data = compact('ticketOwner', 'user', 'ticket', 'comment');

        return $this->deliver();
    }

    public function sendTicketInformation($user, Ticket $ticket)
    {
        $this->to = $user->email;
        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info';
        $this->data = compact('user','ticket');

        return $this->deliver();
    }

    public function sendTicketStatusNotification($ticketOwner, Ticket $ticket)
    {
        $this->to = $ticketOwner->email;
        $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $this->view = 'emails.ticket_status';
        $this->data = compact('ticketOwner','ticket');

        return $this->deliver();
    }


    public function deliver()
    {
        $this->mailer->send($this->view, $this->data, function ($message)
        {
            $message -> from ($this->formAddress, $this->formName)
                     -> to($this->to)->subject($this->subject);
        });
    }
}