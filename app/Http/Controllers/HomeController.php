<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\ReportRepository;
use App\Jobs\ChangeLocale;
use App\Repositories\EmailRepository;
use App\Repositories\NotificationRepository;
use App\Models\Event;
use Paypalpayment;

class HomeController extends Controller {

    protected $reportRepo;
    protected $events;

    /**
     * object to authenticate the call.
     * @param object $_apiContext
     */
    private $_apiContext;

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;
    protected $notificationRepo;

    /**
     * Create a new UserController instance.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @param  App\Repositories\RoleRepository $role_gestion
     * @return void
     */
    public function __construct(UserRepository $userRepo, NotificationRepository $notificationRepo, Event $events, ReportRepository $reportRepo) {
            
        $this->middleware('auth');
        $this->userRepo = $userRepo;
        $this->notificationRepo = $notificationRepo;
        $this->events = $events;
        $this->reportRepo = $reportRepo;
        $this->_apiContext = Paypalpayment::ApiContext(config('paypal_payment.Account.ClientId'), config('paypal_payment.Account.ClientSecret'));
    }

    /**
     * Display the home page.
     *
     * @return Response
     */
    public function index() {
        $authUser = session()->get('user');
        if ($authUser['user_type'] == ADMIN || $authUser['user_type'] == SCHOOL || $authUser['user_type'] == TEACHER || $authUser['user_type'] == QUESTIONADMIN) {
            return redirect(route('dashboard'));
        } else {
            return view('front.front.index');
        }
    }
    
    public function deEnc($id,$en='dec'){echo $test;
        if($en == 'dec'){
           echo decryptParam($id); die;
        }else{
            
        }
    }

    /**
     * Change language.
     *
     * @param  App\Jobs\ChangeLocaleCommand $changeLocaleCommand
     * @return Response
     */
    public function language(
    ChangeLocale $changeLocale) {
        $this->dispatch($changeLocale);

        return redirect()->back();
    }

    /**
     * Display the dashboard page.
     *
     * @return Response
     */
    public function dashboard() {
        //phpinfo(); die;
        /*
          Excel::create('Filename', function($excel) {

          })->export('xls'); die;
         * 
         */

        if (session()->get('user')['user_type'] == STUDENT) {
            return redirect(route('index'));
        }
        if (session()->get('user')['user_type'] == ADMIN) {
            $params = array();
            $userStats = $this->userRepo->showUserTypeTotal($params);
            $userStatsArray = array();
            foreach ($userStats as $key => $val) {
                $userStatsArray[$val['user_type']] = $val['total_count'];
            }
            $data['userStats'] = $userStatsArray;
            $params = array();
            $params['user_type'] = SCHOOL;
            $typeOfSchoolArray = $this->userRepo->getTypeofSchoolCounts($params);
            $typeOfSchoolArray[] = $this->userRepo->getOtherTypeofSchoolCounts($params);
            $data['typeOfSchoolStats'] = $typeOfSchoolArray;
            $params = array();
            $params['user_type'] = SCHOOL;
            $countiesSchoolUsersArray = $this->userRepo->getCountiesUserCounts($params);
            $data['countiesSchoolUsersArray'] = $countiesSchoolUsersArray;
            $params = array();
            $params['user_type'] = TUTOR;
            $countiesParentUsersArray = $this->userRepo->getCountiesUserCounts($params);
            $data['countiesParentUsersStats'] = $countiesParentUsersArray;
            $params = array();
            $params['user_type'] = TUTOR;
            $howHearParantArray = $this->userRepo->getHowHearCounts($params);
            $howHearParantArray[] = $this->userRepo->getOtherHowHearCounts($params);
            $data['howHearParantArray'] = $howHearParantArray;
            $params = array();
            $params['user_type'] = SCHOOL;
            $howHearSchoolArray = $this->userRepo->getHowHearCounts($params);
            $howHearSchoolArray[] = $this->userRepo->getOtherHowHearCounts($params);
            $data['howHearSchoolArray'] = $howHearSchoolArray;
            $param['userType'] = SCHOOL;
            $graph_data['school'] = $this->userRepo->getGraphDataDashboard($param);
            $param['userType'] = TUTOR;
            $graph_data['tutor'] = $this->userRepo->getGraphDataDashboard($param);
            $recordRegistredArray = array();
            foreach ($graph_data as $key => $graph_val) {
                foreach ($graph_val as $record => $graph_value) {
                    if ($key == 'school')
                        $recordRegistredArray[$graph_value->month]['school_total'] = $graph_value->total;
                    if ($key == 'tutor')
                        $recordRegistredArray[$graph_value->month]['tutor_total'] = $graph_value->total;
                }
            }
            $str = "['','School','Parent/Tutor'],";
            foreach ($recordRegistredArray as $key => $graph_val2) {
                $school_total = isset($graph_val2['school_total']) ? $graph_val2['school_total'] : '0';
                $tutor_total = isset($graph_val2['tutor_total']) ? $graph_val2['tutor_total'] : '0';
                $str.= "['" . $key . "'," . $school_total . "," . $tutor_total . "],";
            }
            $data['recordRegistredArray'] = trim($str, ",");
            return view('admin.home.dashboard', $data);
        } else if (session()->get('user')['user_type'] == SCHOOL) {
            $params = array();
            $params['school_id'] = session()->get('user')['id'];
            $userStats = $this->userRepo->showUserTypeTotal($params);
            $userStatsArray = array();
            foreach ($userStats as $key => $val) {
                $userStatsArray[$val['user_type']] = $val['total_count'];
            }
            $data['userStats'] = $userStatsArray;
            $notificationArray = $this->notificationRepo->getLatestNotification(array('user_type' => session()->get('user')['user_type']))->toArray();
            $data['notificationArray'] = $notificationArray;
            return view('admin.home.school_dashboard', $data);
        } else if (session()->get('user')['user_type'] == TEACHER) {
            $params = array();
            $params['teacher_id'] = session()->get('user')['id'];
            $userStats = $this->userRepo->showUserTypeTotal($params);
            $userStatsArray = array();
            foreach ($userStats as $key => $val) {
                $userStatsArray[$val['user_type']] = $val['total_count'];
            }
            $data['userStats'] = $userStatsArray;
            $activeEvents = $this->events->showEvent();
            if (count($activeEvents) > 0) {
                foreach ($activeEvents as $key => $val) {
                    $events[] = array(
                        'autoid' => $val['id'],
                        'title' => $val['title'],
                        'startTime' => $val['start_time'],
                        'endTime' => $val['end_time'],
                        'start' => inputDateFormat($val['start_date']) . 'T' . $val['start_time'],
                        'end' => inputDateFormat($val['start_date']) . 'T' . $val['end_time'],
                    );
                }
                $data['activeEvents'] = json_encode($events);
            } else {
                $data['activeEvents'] = '[]';
            }
            $notificationArray = $this->notificationRepo->getLatestNotification(array('user_type' => session()->get('user')['user_type']))->toArray();
            $data['notificationArray'] = $notificationArray;
            $teacherParams['teacher_id'] = session()->get('user')['id'];
            $data['overdueTask'] = $this->reportRepo->teacherOverviewTaskList($teacherParams);
            return view('admin.home.teacher_dashboard', $data);
        } else if (session()->get('user')['user_type'] == TUTOR) {
            $params = array();
            $params['tutor_id'] = session()->get('user')['id'];
            $userStats = $this->userRepo->showUserTypeTotal($params);
            $userStatsArray = array();
            foreach ($userStats as $key => $val) {
                $userStatsArray[$val['user_type']] = $val['total_count'];
            }
            $data['userStats'] = $userStatsArray;
            $notificationArray = $this->notificationRepo->getLatestNotification(array('user_type' => session()->get('user')['user_type']))->toArray();
            $data['notificationArray'] = $notificationArray;

            return view('admin.home.tutor_dashboard', $data);
        } else if (session()->get('user')['user_type'] == QUESTIONADMIN) {
            $params = array();
            $userStats = $this->userRepo->showUserTypeTotal($params);
            $userStatsArray = array();
            foreach ($userStats as $key => $val) {
                $userStatsArray[$val['user_type']] = $val['total_count'];
            }
            $data['userStats'] = $userStatsArray;
            return view('admin.home.qa_dashboard', $data);
        } else if (session()->get('user')['user_type'] == QUESTIONVALIDATOR) {
            $params = array();
            $userStats = $this->userRepo->showUserTypeTotal($params);
            $userStatsArray = array();
            foreach ($userStats as $key => $val) {
                $userStatsArray[$val['user_type']] = $val['total_count'];
            }
            $data['userStats'] = $userStatsArray;
            return view('admin.home.qa_dashboard', $data);
        }
    }

    public function dashboardSchool() {
        $params = array();
        $params['status'] = ACTIVE;
        $userStats = $this->userRepo->showUserTypeTotal($params);
        foreach ($userStats as $key => $val) {
            $userStatsArray[$val['user_type']] = $val['total_count'];
        }
        $data['userStats'] = $userStatsArray;
        return view('admin.home.dashboardSchool', $data);
    }

    /**
     * use to test send email functionality
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     */
    public function testEmail(EmailRepository $emailRepo) {
        $emailParam = array(
            'addressData' => array(
                //'to_email' => 'niteshk@icreon.com',
                'to_email' => 'vignesh.t@sedinfotech.ch',
                'to_name' => 'Dev2'
            ),
            'userData' => array(
                'first_name' => 'Nitesh'
            )
        );
        $emailRepo->sendEmail($emailParam, 1);
        echo "Email has been sent successfully";
    }

    /*
     * Display form to process payment using credit card
     */

    public function create() {
        return View::make('payment.order');
    }

    /*
     * Process payment using credit card
     */

    public function store() {
        // ### Address
        // Base Address object used as shipping or billing
        // address in a payment. [Optional]
        $addr = Paypalpayment::address();
        $addr->setLine1("3909 Witmer Road");
        $addr->setLine2("Niagara Falls");
        $addr->setCity("Niagara Falls");
        $addr->setState("NY");
        $addr->setPostalCode("14305");
        $addr->setCountryCode("US");
        $addr->setPhone("716-298-1822");

        // ### CreditCard
        $card = Paypalpayment::creditCard();
        $card->setType("visa")
                ->setNumber("4758411877817150")
                ->setExpireMonth("05")
                ->setExpireYear("2019")
                ->setCvv2("456")
                ->setFirstName("Joe")
                ->setLastName("Shopper");

        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // Use a Payer ID (A unique identifier of the payer generated
        // and provided by the facilitator. This is required when
        // creating or using a tokenized funding instrument)
        // and the `CreditCardDetails`
        $fi = Paypalpayment::fundingInstrument();
        $fi->setCreditCard($card);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // Use the List of `FundingInstrument` and the Payment Method
        // as 'credit_card'
        $payer = Paypalpayment::payer();
        $payer->setPaymentMethod("credit_card")
                ->setFundingInstruments(array($fi));

        $item1 = Paypalpayment::item();
        $item1->setName('Ground Coffee 40 oz')
                ->setDescription('Ground Coffee 40 oz')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setTax(0.3)
                ->setPrice(7.50);

        $item2 = Paypalpayment::item();
        $item2->setName('Granola bars')
                ->setDescription('Granola Bars with Peanuts')
                ->setCurrency('USD')
                ->setQuantity(5)
                ->setTax(0.2)
                ->setPrice(2);


        $itemList = Paypalpayment::itemList();
        $itemList->setItems(array($item1, $item2));


        $details = Paypalpayment::details();
        $details->setShipping("1.2")
                ->setTax("1.3")
                //total of items prices
                ->setSubtotal("17.5");

        //Payment Amount
        $amount = Paypalpayment::amount();
        $amount->setCurrency("USD")
                // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
                ->setTotal("20")
                ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $transaction = Paypalpayment::transaction();
        $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription("Payment description")
                ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'

        $payment = Paypalpayment::payment();

        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setTransactions(array($transaction));

        try {
            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;
            $payment->create($this->_apiContext);
        } catch (\PPConnectionException $ex) {
            return "Exception: " . $ex->getMessage() . PHP_EOL;
            exit(1);
        }

        dd($payment);
    }

}
