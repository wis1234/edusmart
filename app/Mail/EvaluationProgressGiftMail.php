<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\ParentModel;
use App\Models\Evaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationProgressGiftMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $parent;
    public $evaluation;
    public $subject;
    public $shopUrl;

    public function __construct(Student $student, ParentModel $parent, Evaluation $evaluation)
    {
        $this->student = $student;
        $this->parent = $parent;
        $this->evaluation = $evaluation;
        $this->subject = $evaluation->subject;
        $this->shopUrl = route('ecommerce.index');
    }

    public function build()
    {
        return $this->subject('Félicitations pour le progrès de ' . $this->student->full_name)
            ->markdown('emails.gift_suggestion');
    }
} 