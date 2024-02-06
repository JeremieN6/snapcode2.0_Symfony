<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Contact;
use App\Entity\MoreInfoFormulaireController;
use App\Form\ContactType;
use App\Form\MoreInfoFormType;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        NewsletterRepository $newsletterRepository,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy): Response
    {

        $formNewsletter = $this->createForm(NewsletterType::class);
        $formNewsletter->handleRequest($request);

        if($formNewsletter->isSubmitted() && $formNewsletter->isValid()){
            $email = $formNewsletter->get('email')->getData();

            $emailExist = $newsletterRepository->findOneBy(['email' => $email]);

            if($emailExist){
                $flashy->warning('Cet e-mail est déjà inscrit à la Newsletter.');
                return $this->redirectToRoute('app_home');
            }else{
                $newsletterEmail = new Newsletter();
                $newsletterEmail->setEmail($email);
    
                $entityManager->persist($newsletterEmail);
                $entityManager->flush();
    
                $flashy->success('Vous avez été ajouté à la Newsletter !');
    
                return $this->redirectToRoute('app_home');
            }
        } elseif ($formNewsletter->isSubmitted() && !$formNewsletter->isValid()) {
            $flashy->error('Une erreur est survenue lors de l\'ajout à la Newsletter.');
        }

        $formMoreInfo = $this->createForm(MoreInfoFormType::class);
        $formMoreInfo->handleRequest($request);

        if ($formMoreInfo->isSubmitted() && $formMoreInfo->isValid()){
            $data = $formMoreInfo->getData();

            $serviceType1MoreInfoForm = $data['serviceTypeSuperSite']; // Type de service 1
            $serviceType2MoreInfoForm = $data['serviceTypeAutre']; // Type de service 2
            $nomMoreInfoForm = $data['nom']; // Nom du client
            $prenomMoreInfoForm = $data['prenom']; // Nom du client
            $emailMoreInfoForm = $data['email']; // Adresse Email
            $phoneMoreInfoForm = $data['telephone']; // Téléphone
            $websiteMoreInfoForm = $data['website']; // Siteweb du client
            $descriptionMoreInfoForm = $data['description']; // Description

            // Enregistrer les informations du formulaire dans la base de donnée
            // Crée une nouvelle instance de l'entité Contact
                $moreInfo = new MoreInfoFormulaireController();
                $moreInfo->setServiceTypeSuperSite($data['serviceTypeSuperSite']);
                $moreInfo->setServiceTypeAutre($data['serviceTypeAutre']);
                $moreInfo->setNom($data['nom']);
                $moreInfo->setPrenom($data['prenom']);
                $moreInfo->setEmail($data['email']);
                $moreInfo->setWebsite($data['website']);
                $moreInfo->setTelephone($data['telephone']);
                $moreInfo->setDescription($data['description']);

                // Enregistre l'entité dans la base de données
                $entityManager->persist($moreInfo);
                $entityManager->flush();

                // Utilisez Flashy pour afficher un message flash de succès
                $flashy->success('Votre formulaire nous a bien été envoyé ✅ !');

                // Redirigez l'utilisateur vers la même page (rafraîchissement)
                return $this->redirectToRoute('app_home');

        }elseif ($formMoreInfo->isSubmitted() && !$formMoreInfo->isValid()) {
            $flashy->error('Une erreur est survenue lors de l\'envoie du formulaire. Veuillez réessayer.');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'formNewsletter' => $formNewsletter->createView(),
            'formMoreInfo' => $formMoreInfo->createView(),
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request, 
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        \MercurySeries\FlashyBundle\FlashyNotifier $flashy): Response
    {
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()){
            $data = $contactForm->getData();

            $senderEmail = 'contact@snapcode.jeremiecode.fr'; // Adresse de l'expéditeur
            $mailAdress = $data['email']; // Adresse du destinataire

            $senderName = $data['nom']; // Nom du destinataire
            $phoneNumber = $data['phone']; // Téléphone du destinataire
            $companyName = $data['companyName']; // Nom Entreprise du destinataire

            $emailMessage = 'Email envoyé par : ' . $senderName . "\n\nAdresse email : " .$senderEmail. "\n\n" . $data['message'];

            $email = (new Email())
                ->from($senderEmail)
                ->to('contact@snapcode.jeremiecode.fr')
                ->subject('Email de contact de SnapCode™ Agency')
                ->text($emailMessage);

                $mailer->send($email);


            // Enregistrer les informations du formulaire dans la base de donnée
                // Crée une nouvelle instance de l'entité Contact
                $contact = new Contact();
                $contact->setNom($data['nom']);
                $contact->setEmail($data['email']);
                $contact->setCompanyName($data['companyName']);
                $contact->setPhoneNumberCompany($data['phone']);
                $contact->setEmailMessage($data['message']);

                // Enregistre l'entité dans la base de données
                $entityManager->persist($contact);
                $entityManager->flush();

                // Utilisez Flashy pour afficher un message flash de succès
                $flashy->success('Votre email a bien été envoyé ✅ !');

                // Redirigez l'utilisateur vers la même page (rafraîchissement)
                return $this->redirectToRoute('app_home');

        }elseif ($contactForm->isSubmitted() && !$contactForm->isValid()) {
            $flashy->error('Une erreur est survenue lors de l\'envoie du mail. Veuillez réessayer.');
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'HomeController',
            'contactForm' => $contactForm->createView()
        ]);
    }

    #[Route('/abonnement', name: 'abonnement')]
    public function abonnement(PlanRepository $planRepository): Response
    {
        $plan = $planRepository->findAll();

        return $this->render('plan/plans.html.twig', [
            'Plan' => $plan,
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/a-propos', name: 'about')]
    public function about(): Response
    {
        return $this->render('home/aboutUs.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/portfolio', name: 'portfolio')]
    public function portfolio(): Response
    {
        return $this->render('home/portfolio.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/equipe', name: 'team')]
    public function team(): Response
    {
        return $this->render('home/team.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/tarifs', name: 'pricing')]
    public function pricing(): Response
    {
        return $this->render('home/pricing.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
