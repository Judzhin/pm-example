<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Works\Projects\Project\Setting;

use App\Annotation\UUIDv4;
use App\Entity\Work\Project;
use App\UseCase\Work\Project\Membership;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MembersController
 * @package App\Controller\Works\Projects\Project\Setting
 *
 * @Route("/work/project/{project_id}/setting/members", requirements={"id"=UUIDv4::PATTERN})
 * @ParamConverter("project", options={"id" = "project_id"})
 * @IsGranted("ROLE_WORK_MANAGE_PROJECTS")
 */
class MembersController extends AbstractController
{
    /**
     * @Route("", name="pm_work_project_setting_members")
     *
     * @param Project $project
     * @return Response
     */
    public function index(Project $project): Response
    {
        return $this->render('works/projects/project/setting/members/index.html.twig', compact('project'));
    }

    /**
     * @Route("/assign", name="pm_work_project_setting_member_assign")
     *
     * @param Project $project
     * @param Request $request
     * @param Membership\Assign\Handler $handler
     * @return Response
     */
    public function assign(Project $project, Request $request, Membership\Assign\Handler $handler): Response
    {
        // $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);

        if (!$project->getDepartments()) {

        }

//        if (!$project->getDepartments()) {
//            $this->addFlash('error', 'Add departments before adding members.');
//            return $this->redirectToRoute('work.projects.project.settings.members', ['project_id' => $project->getId()]);
//        }
//
        /** @var Membership\Assign\Command $command */
        $command = new Membership\Assign\Command;
        $command->project = $project->getId()->toString();

        /** @var FormInterface $form */
        $form = $this->createForm(Membership\Assign\FormType::class, $command, ['project' => $project->getId()->toString()]);
        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            try {
//                $handler->handle($command);
//                return $this->redirectToRoute('work.projects.project.settings.members', ['project_id' => $project->getId()]);
//            } catch (\DomainException $e) {
//                $this->errors->handle($e);
//                $this->addFlash('error', $e->getMessage());
//            }
//        }
//
//        return $this->render('app/work/projects/project/settings/members/assign.html.twig', [
//            'project' => $project,
//            'form' => $form->createView(),
//        ]);
    }

//    /**
//     * @Route("/{member_id}/edit", name=".edit")
//     * @param Project $project
//     * @param string $member_id
//     * @param Request $request
//     * @param Membership\Edit\Handler $handler
//     * @return Response
//     */
//    public function edit(Project $project, string $member_id, Request $request, Membership\Edit\Handler $handler): Response
//    {
//        $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);
//
//        $membership = $project->getMembership(new Id($member_id));
//
//        $command = Membership\Edit\Command::fromMembership($project, $membership);
//
//        $form = $this->createForm(Membership\Edit\Form::class, $command, ['project' => $project->getId()->getValue()]);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            try {
//                $handler->handle($command);
//                return $this->redirectToRoute('work.projects.project.settings.members', ['project_id' => $project->getId()]);
//            } catch (\DomainException $e) {
//                $this->errors->handle($e);
//                $this->addFlash('error', $e->getMessage());
//            }
//        }
//
//        return $this->render('app/work/projects/project/settings/members/edit.html.twig', [
//            'project' => $project,
//            'membership' => $membership,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{member_id}/revoke", name=".revoke", methods={"POST"})
//     * @param Project $project
//     * @param string $member_id
//     * @param Request $request
//     * @param Membership\Remove\Handler $handler
//     * @return Response
//     */
//    public function revoke(Project $project, string $member_id, Request $request, Membership\Remove\Handler $handler): Response
//    {
//        $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);
//
//        if (!$this->isCsrfTokenValid('revoke', $request->request->get('token'))) {
//            return $this->redirectToRoute('work.projects.project.settings.departments', ['project_id' => $project->getId()]);
//        }
//
//        $command = new Membership\Remove\Command($project->getId()->getValue(), $member_id);
//
//        try {
//            $handler->handle($command);
//        } catch (\DomainException $e) {
//            $this->errors->handle($e);
//            $this->addFlash('error', $e->getMessage());
//        }
//
//        return $this->redirectToRoute('work.projects.project.settings.members', ['project_id' => $project->getId()]);
//    }
//
//    /**
//     * @Route("/{member_id}", name=".show", requirements={"member_id"=Guid::PATTERN}))
//     * @param Project $project
//     * @return Response
//     */
//    public function show(Project $project): Response
//    {
//        return $this->redirectToRoute('work.projects.project.settings.members', ['project_id' => $project->getId()]);
//    }
}