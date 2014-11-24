<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Form\Type\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Group controller.
 *
 * @Route("/group")
 */
class GroupController extends Controller
{
    /**
     * Lists all groups.
     *
     * @Route("/", name="app_group_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $groupManager = $this->get('fos_user.group_manager');

        $groups = $groupManager->findGroups();

        return $this->render(
            'AppBundle:Group:list.html.twig',
            ['groups' => $groups]
        );
    }

    /**
     * Add and create a new group.
     *
     * @Route("/add", name="app_group_add")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $groupManager = $this->get('fos_user.group_manager');

        $group = $groupManager->createGroup('New');

        $form = $this->createForm(
            new GroupType(),
            $group,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('app_group_add')
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupManager->updateGroup($group);

            return $this->redirect($this->generateUrl('app_group_list'));
        }

        return $this->render(
            'AppBundle:Group:add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit and update a group.
     *
     * @Route("/{group}/edit", name="app_group_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Request $request, Group $group)
    {
        $form = $this->createForm(
            new GroupType(),
            $group,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('app_group_edit', ['group' => $group->getId()])
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupManager = $this->get('fos_user.group_manager');

            $groupManager->updateGroup($group);

            return $this->redirect($this->generateUrl('app_group_list'));
        }

        return $this->render(
            'AppBundle:Group:edit.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Delete a group.
     *
     * @Route("/{group}/delete", name="app_group_delete")
     * @Method("GET")
     */
    public function deleteAction(Group $group)
    {
        if (0 > count($group->getUsers())) {
            throw new \Exception('You cannot delete the group while it has any members in it.');
        }

        $groupManager = $this->get('fos_user.group_manager');
        $groupManager->deleteGroup($group);

        return $this->redirect($this->generateUrl('app_group_list'));
    }
}
