=) API for reading data from json file Details:

	- Endpoint: /api/fetchJsonData
	- Request type: POST
	- Parameters:
		- para_filename (mendatory) => CSV filename from the directory 'resources/csvfiles'. Value ex: data.csv
		- para_name (optional) => Enter the name if want to search the result by name. Value ex: Test Row
		- para_percentage (optional) => Enter the percentage value if want to search the result by percentage. Value ex: 30


	- Improvement suggetions: The API currently is without any authorization, I did not made the authorization becasue it will require additional time making APIs for authentication of users.  


=) CLI Command for generating JSON file from CSV:

	- Command: php artisan generate:file {source file name} {generate type} ex. command: 'php artisan generate:file data.csv json'
		- {source file name} = CSV filename from the directory 'resources/csvfiles'. Value ex: data.csv
		- {generate type} = File generate type. Value ex: json or xml

	- You will find the generated file here: 'resources/jsonfiles'

	Note: Currently I only implemented the json file generation, xml file generation can be done similarly using the xml library.