<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Login;
use App\Repository\EmployeeRepository;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/employees', name: 'app_api_employee')]
class ApiEmployeeController extends AbstractController
{
    /**
     * @param Request $request
     * @param EmployeeRepository $employeeRepository
     * @return JsonResponse
     */
    #[Route('', name: 'api_employee_index',methods: ['GET'])]
    public function index(Request $request, EmployeeRepository $employeeRepository): JsonResponse
    {
        $employees = $employeeRepository->findAll();
        if(empty($employees)){
            $employeesJson = [
                "status" => "fail",
                "data" => $employees,
                "message" => "Employees is null or empty"
            ];
            $status = Response::HTTP_OK;
        }
        else{
            $employeesJson = [
                "status" => "success",
                "data" => $employees,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }
        return new JsonResponse($employeesJson, $status);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'api_employee_show', methods: ['GET'])]
    public function show(Request $request,  ?Employee $employee): JsonResponse
    {
        if(empty($employee)){
            $employeesJson = [
                "status" => "fail",
                "data" => $employee,
                "message" => "Employee is null or empty"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        else{
            $employeesJson = [
                "status" => "success",
                "data" => $employee,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }
            return new JsonResponse($employeesJson, $status);
    }


    #[Route('', name: 'api_employee_new', methods: ['POST'])]
    public function create(Request $request,EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        if(empty($request->getContent())){
            $employeesJson = [
                "status" => "fail",
                "data" => $request->getContent(),
                "message" => "request data is null or empty"
            ];
            $status = Response::HTTP_OK;
        }
        else{
            $employee = new Employee();
            $data = [];
            if ($content = $request->getContent()) {
                $data = json_decode($content, true);
            }

            try {
                $employee->setName($data["name"]);
                $employee->setLastname($data["lastname"]);
                $employee->setType($data["type"]);
                $employee->setDischarge(false);
                $login = new Login();
                $employee->setLogin($login);
                $employee->getLogin()->setUsername($data["username"]);
                $employee->getLogin()->setPassword($data["password"]);
                $employee->getLogin()->setRole('ROLE_ADMINISTRATIVE');

                $violations = $validator->validate($employee);
                if (count($violations) > 0) {
                    $employeesJson = [
                        "status" => "error",
                        "data" => $violations,
                        "message" => ''
                    ];
                    $status = Response::HTTP_BAD_REQUEST;
                    return new JsonResponse($employeesJson, $status);
                }

            } catch (\Exception $e) {
                $employeesJson = [
                    "status" => "error",
                    "data" => $employee,
                    "message" => $e
                ];
                $status = Response::HTTP_BAD_REQUEST;
                return new JsonResponse($employeesJson, $status);
            }
            $employeesJson = [
                "status" => "success",
                "data" => $employee,
                "message" => ""
            ];

            $em->persist($employee);
            $em->flush();

            $status = Response::HTTP_CREATED;
        }

        return new JsonResponse($employeesJson, $status);
    }


}
