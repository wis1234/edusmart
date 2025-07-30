@component('mail::message')
# Félicitations pour le progrès de {{ $student->full_name }} !

Bonjour {{ $parent->full_name }},

Nous sommes heureux de vous informer que {{ $student->full_name }} a amélioré sa note en {{ $subject->name }} lors de la dernière évaluation ({{ $evaluation->score }}).

Pour l'encourager, pourquoi ne pas lui offrir un cadeau ?

@component('mail::button', ['url' => $shopUrl])
Offrir un cadeau maintenant
@endcomponent

Merci pour votre engagement et votre soutien !

Cordialement,
L'équipe pédagogique
@endcomponent 