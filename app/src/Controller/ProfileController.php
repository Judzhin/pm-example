<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="pm_profile")
     */
    public function index()
    {
        /** @var User $user */
        $user = $this
            ->getDoctrine()
            ->getManager()
            ->find(User::class, $this->getUser()->getId());

        return $this->render('profile/index.html.twig', compact('user'));
    }
}
