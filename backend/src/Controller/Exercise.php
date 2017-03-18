<?php

namespace AcademiaDigital\Controller;
use Slim\Http\Response;
use Slim\Http\Request;
use AcademiaDigital\Service;

class Exercise extends Controller
{
	protected $exerciseService;

	public function __construct(Service\Exercise $exerciseService)
	{
		$this->exerciseService = $exerciseService;
	}

	public function getAll(Request $request, Response $response)
	{
		$exercises = $this->exerciseService->getAll();
		return $this->respond($response, $exercises);
	}

	public function getOne(Request $request, Response $response, array $args)
	{

	}

	public function create(Request $request, Response $response)
	{
		$body = $request->getParsedBody();
		$name = $body["name"];
		$muscleGroupId = $body["muscleGroup"];

		$this->exerciseService->create($name, $muscleGroupId);
		return $this->respond($response, ["message" => "Exercise created successfully"]);
	}

	public function update(Request $request, Response $response, array $args)
	{

	}

	public function delete(Request $request, Response $response, array $args)
	{

	}
}
