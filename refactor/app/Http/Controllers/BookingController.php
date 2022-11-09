<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;
use Exception;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    private $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * Index is the first function on the page to be executed
     */
    public function index(Request $request)
    {
        try {
            if ($user_id = $request->get('user_id')) {
                $response = $this->repository->getUsersJobs($user_id);
            } elseif ($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID')) {
                $response = $this->repository->getAll($request);
            }

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * 
     * This function fetchs the job by id
     */
    public function show($id)
    {
        try {
            $job = $this->repository->with('translatorJobRel.user')->find($id);

            return response($job);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used store booking data
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $response = $this->repository->store($request->__authenticatedUser, $data);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }


    /**
     * @param $id
     * @param Request $request
     * @return mixed
     * 
     * This function is used update booking data
     */
    public function update($id, Request $request)
    {
        try {
            $data = $request->all();
            $cuser = $request->__authenticatedUser;
            $response = $this->repository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function fetch all immediate job emails
     */
    public function immediateJobEmail(Request $request)
    {
        $data = $request->all();

        $response = $this->repository->storeJobEmail($data);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to get user job history by user id
     */
    public function getHistory(Request $request)
    {
        try {
            if ($user_id = $request->get('user_id')) {
                $response = $this->repository->getUsersJobsHistory($user_id, $request);
                return response($response);
            }
            return null;
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to accept job
     */
    public function acceptJob(Request $request)
    {
        try {
            $data = $request->all();
            $user = $request->__authenticatedUser;

            $response = $this->repository->acceptJob($data, $user);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to accept job with job id
     */
    public function acceptJobWithId(Request $request)
    {
        try {
            $data = $request->get('job_id');
            $user = $request->__authenticatedUser;

            $response = $this->repository->acceptJobWithId($data, $user);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to cancel job
     */
    public function cancelJob(Request $request)
    {
        try {
            $data = $request->all();
            $user = $request->__authenticatedUser;

            $response = $this->repository->cancelJobAjax($data, $user);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to end job
     */
    public function endJob(Request $request)
    {
        try {
            $data = $request->all();

            $response = $this->repository->endJob($data);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to get customers in marked as not call
     */
    public function customerNotCall(Request $request)
    {
        try {
            $data = $request->all();

            $response = $this->repository->customerNotCall($data);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to get potential jobs
     */
    public function getPotentialJobs(Request $request)
    {
        try {
            $user = $request->__authenticatedUser;

            $response = $this->repository->getPotentialJobs($user);

            return response($response);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to get distance feed
     */
    public function distanceFeed(Request $request)
    {
        try {
            $data = $request->all();
        
            $flagged = "no";

            $distance = (isset($data['distance']) && !empty($data['distance'])) ? $data['distance'] : null;

            $time = (isset($data['time']) && !empty($data['time'])) ? $data['time'] : null

            $jobid = (isset($data['jobid']) && !empty($data['jobid'])) ? $data['jobid'] : null;

            $session = (isset($data['session_time']) && !empty($data['session_time'])) ? $data['session_time'] : null;

            if ($data['flagged'] == 'true') {
                if ($data['admincomment'] == '') {
                    return "Please, add comment";
                }
                $flagged = 'yes';
            }

            $manually_handled = ($data['manually_handled'] == 'true') ? 'yes' : 'no';
            $by_admin = ($data['by_admin'] == 'true') ? 'yes' : 'no';
            $admincomment = (isset($data['admincomment']) && !empty($data['admincomment'])) ? $data['admincomment'] : "";


            if (!empty($time) || !empty($distance)) {
                Distance::where('job_id', '=', $jobid)->update(array('distance' => $distance, 'time' => $time));
            }

            if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {
                Job::where('id', '=', $jobid)->update(array('admin_comments' => $admincomment, 'flagged' => $flagged, 'session_time' => $session, 'manually_handled' => $manually_handled, 'by_admin' => $by_admin));
            }

            return response('Record updated!');
        }
        catch(Exception $ex){
            return response(['message' => $ex->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to reopen job
     */
    public function reopen(Request $request)
    {
       try {
            $data = $request->all();
            $response = $this->repository->reopen($data);

            return response($response);
       } catch(Exception $ex){
            return response(['message' => $ex->getMessage()]);
       }
    }

    /**
     * @param Request $request
     * @return mixed
     * 
     * This function is used to resend notification to translator
     */
    public function resendNotifications(Request $request)
    {
        try {
            $data = $request->all();
            $job = $this->repository->find($data['jobid']);
            $job_data = $this->repository->jobToData($job);
            $this->repository->sendNotificationTranslator($job, $job_data, '*');

            return response(['success' => 'Push sent']);
       } catch(Exception $ex){
            return response(['message' => $ex->getMessage()]);
       }
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * 
     * This function is used to resend sms notification
     */
    public function resendSMSNotifications(Request $request)
    {
        try {
            $data = $request->all();
            $job = $this->repository->find($data['jobid']);
            $job_data = $this->repository->jobToData($job);
            $this->repository->sendSMSNotificationToTranslator($job);
            
            return response(['success' => 'SMS sent']);
        } catch (Exception $ex) {
            return response(['message' => $ex->getMessage()]);
        }
    }
}
