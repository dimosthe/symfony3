<?php
namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;

class CustomResponse
{
	private $message;
	private $messageCode;
	private $status;
	private $statusCode;
	private $data;

	const invalidData 		= 0;
	const produceTest 		= 1;
	const productsList 		= 2;
	const productCreated 	= 3;

	public static $messages = array(
		self::invalidData 		=> 'invalidData',
		self::produceTest 		=> 'Test backgroud jobs have created successfully',
		self::productsList 		=> 'Products list fetched successfully',
		self::productCreated 	=> 'Product created successfully'
	);

	public function __construct($messageCode, $statusCode = null, $status = null, $data = null)
	{
		$this->message 		= self::$messages[$messageCode];
		$this->messageCode	= $messageCode;
		$this->status 		= $status;
		$this->statusCode	= $statusCode;
		$this->data 		= $data;
	}

	public function message()
	{
		return $this->message;
	}

	public function create()
	{
		$res = array(
			'status'		=> $this->status,
			'message'		=> $this->message,
			'messageCode' 	=> $this->messageCode
		);

		if(!empty($this->data))
			$res = array_merge($res, $this->data);

		$response = new JsonResponse();

        $response->setData(array(
            'meta' => array(
                'code' 	=> $this->statusCode,
                'in'	=> round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4)
            ),
            'response' => $res
        ));

        return $response;
	} 
}