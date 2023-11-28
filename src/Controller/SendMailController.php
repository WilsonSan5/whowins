<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SendMailController extends AbstractController
{
    #[Route('send/mail', name: 'app_send_mail', methods: 'POST')]
    public function sendMail(MailerInterface $mailer, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $mail = (new TemplatedEmail())
            ->from($data['email'])
            ->to('wilsonsan.dev@gmail.com')
            ->subject('WhoWins Contact form')
            ->htmlTemplate('send_mail/message-template.twig')
            ->context([
                'user_email' => $data['email'],
                'objet' => 'WhoWins Contact form',
                'message' => $data['message']
            ]);
        $mailer->send($mail);

        return new Response('Your message has been sent !', 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
