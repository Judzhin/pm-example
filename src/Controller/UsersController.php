<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController
 * @package App\Controller
 */
class UsersController extends AbstractController
{
    /** @var UserRepository */
    private $repository;

    /**
     * UsersController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        /** @var array $users */
        $users = $this->repository->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }
}
