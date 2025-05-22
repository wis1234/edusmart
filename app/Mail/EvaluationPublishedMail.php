<?php

namespace App\Mail;

use App\Models\StudentGrade;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentGrade;

    /**
     * Create a new message instance.
     *
     * @param StudentGrade $studentGrade
     * @return void
     */
    public function __construct(StudentGrade $studentGrade)
    {
        $this->studentGrade = $studentGrade;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Evaluation Details Published')
                    ->view('emails.evaluation_published');
    }
}
