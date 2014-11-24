<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Warehouse controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all users.
     *
     * @Route("/", name="app_user_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');

        $users = $userManager->findUsers();

        return $this->render(
            'AppBundle:User:list.html.twig',
            ['users' => $users]
        );
    }

    /**
     * Add and create a new user.
     *
     * @Route("/add", name="app_user_add")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();

        $form = $this->createForm(
            new UserType(),
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('app_user_add')
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);

            return $this->redirect($this->generateUrl('app_user_list'));
        }

        return $this->render(
            'AppBundle:User:add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit and update a user.
     *
     * @Route("/{user}/edit", name="app_user_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(
            new UserType(),
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('app_user_edit', ['user' => $user->getId()])
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');

            $userManager->updatePassword($user);
            $userManager->updateUser($user);

            return $this->redirect($this->generateUrl('app_user_list'));
        }

        return $this->render(
            'AppBundle:User:edit.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Delete a user.
     *
     * @Route("/{user}/delete", name="app_user_delete")
     * @Method("GET")
     */
    public function deleteAction(User $user)
    {
        $userManager = $this->get('fos_user.user_manager');
        $userManager->deleteUser($user);

        return $this->redirect($this->generateUrl('app_user_list'));
    }
}
