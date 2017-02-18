"use strict";

(function(){

angular
	.module("Customers")
	.controller("CustomersController", [
		"$scope", "CustomersService",
		Controller
	]);

function Controller($scope, CustomersService)
{

	(function initialize(){
		$scope.customers = CustomersService.all();
		$scope.columns = [
			{ value: "id", text: "#" },
			{ value: "name", text: "Nome" },
			{ value: "registry", text: "Matrícula" }
		];
	})();

	$scope.addCustomer = function()
	{
		console.log("Adding a customer...");
	};

	$scope.editCustomer = function()
	{
		console.log("Editing a customer...");
	};

	$scope.deleteCustomer = function()
	{
		console.log("Deleting a customer...");
	};

}

})();