<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Controller\Work\Project\Setting;

use App\Annotation\UUIDv4;
use App\Entity\Work\Project;
use App\UseCase\Work\Project\Department\Create;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DepartmentsController
 * @package App\Controller\Work\Project\Setting
 *
 * @Route("/work/project/{project_id}/setting/departaments", requirements={"id"=UUIDv4::PATTERN})
 * @ParamConverter("project", options={"id" = "project_id"})
 * @IsGranted("ROLE_WORK_MANAGE_PROJECTS")
 */
class DepartmentsController extends AbstractController
{
    /**
     * @Route("", name="pm_work_project_setting_departments")
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project): Response
    {
        return $this->render('works/projects/project/setting/departments/index.html.twig', compact('project'));
    }

    /**
     * @Route("/create", name="pm_work_project_setting_department_create")
     *
     * @param Project $project
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     * @throws \Throwable
     */
    public function create(Project $project, Request $request, Create\Handler $handler): Response
    {
        /** @var Create\Command $command */
        $command = new Create\Command;
        $command->project = $project;

        /** @var FormInterface|Create\FormType $form */
        $form = $this->createForm(Create\FormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);
            return $this->redirectToRoute('pm_work_project_setting_departments', ['project_id' => $project->getId()]);
        }

        return $this->render('works/projects/project/setting/departments/create.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

//    /**
//     * @Route("/edit", name="pm_work_project_setting_edit")
//     *
//     * @param Project $project
//     * @param Request $request
//     * @param Edit\Handler $handler
//     * @return Response
//     */
//    public function edit(Project $project, Request $request, Edit\Handler $handler): Response
//    {
//        /** @var array $parameters */
//        $parameters = ['id' => $project->getId()];
//
//        /** @var Edit\Command $command */
//        $command = Edit\Command::parse($project);
//
//        /** @var FormInterface|Edit\FormType $form */
//        $form = $this->createForm(Edit\FormType::class, $command);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid())  {
//            $handler->handle($command);
//            return $this->redirectToRoute('pm_work_project', $parameters);
//        }
//
//        return $this->render('works/projects/project/setting/edit.html.twig', [
//            'form' => $form->createView(),
//            'project' => $project
//        ]);
//    }
//
//    /**
//     * @Route("/archive", name="pm_work_project_setting_archive", methods={"POST"})
//     *
//     * @param Project $project
//     * @param Request $request
//     * @param Archived\Handler $handler
//     * @return Response
//     */
//    public function archive(Project $project, Request $request, Archived\Handler $handler): Response
//    {
//        /** @var array $arguments */
//        $arguments = ['id' => $project->getId()];
//
//        if (!$this->isCsrfTokenValid('archive', $request->request->get('token'))) {
//            return $this->redirectToRoute('pm_work_project', $arguments);
//        }
//
//        // $this->denyAccessUnlessGranted(ProjectAccess::EDIT, $project);
//
//        /** @var Archived\Command $command */
//        $command = new Archived\Command($project->getId());
//
//        try {
//            $handler->handle($command);
//        } catch (\DomainException $e) {
//            // $this->errors->handle($e);
//            // $this->addFlash('error', $e->getMessage());
//        }
//
//        return $this->redirectToRoute('pm_work_project_setting', $arguments);
//    }
//
//    /**
//     * @Route("/reinstate", name="pm_work_project_setting_reinstate", methods={"POST"})
//     *
//     * @param Project $project
//     * @param Request $request
//     * @param Reinstate\Handler $handler
//     * @return Response
//     * @throws \Throwable
//     */
//    public function reinstate(Project $project, Request $request, Reinstate\Handler $handler): Response
//    {
//        /** @var array $arguments */
//        $arguments = ['id' => $project->getId()];
//
//        if (!$this->isCsrfTokenValid('reinstate', $request->request->get('token'))) {
//            return $this->redirectToRoute('work.projects.project.settings', $arguments);
//        }
//
//        // $this->denyAccessUnlessGranted(ProjectAccess::EDIT, $project);
//
//        /** @var Reinstate\Command $command */
//        $command = new Reinstate\Command($project->getId());
//
//        try {
//            $handler->handle($command);
//        } catch (\DomainException $e) {
//        }
//
//        return $this->redirectToRoute('pm_work_project_setting', $arguments);
//    }
//
//    /**
//     * @Route("/delete", name="pm_work_project_setting_delete", methods={"POST"})
//     *
//     * @param Project $project
//     * @param Request $request
//     * @param Remove\Handler $handler
//     * @return Response
//     */
//    public function delete(Project $project, Request $request, Remove\Handler $handler): Response
//    {
//        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
//            return $this->redirectToRoute('pm_work_project_setting', ['id' => $project->getId()]);
//        }
//
//        // $this->denyAccessUnlessGranted(ProjectAccess::EDIT, $project);
//
//        /** @var Remove\Command $command */
//        $command = new Remove\Command($project->getId()->toString());
//
//        try {
//            $handler->handle($command);
//        } catch (\DomainException $e) {
//            // $this->errors->handle($e);
//            // $this->addFlash('error', $e->getMessage());
//        }
//
//        return $this->redirectToRoute('pm_work_projects');
//    }
}