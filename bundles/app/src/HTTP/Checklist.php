<?php

namespace Project\App\HTTP;

use PHPixie\HTTP\Request;
use PHPixie\Validate\Form;

/**
 * Listing and posting messages
 */
class Checklist extends Processor
{
    /**
     * Display latest messages
     *
     * @param Request $request HTTP request
     * @return mixed
     */
    public function defaultAction($request)
    {
        $user = $this->user();
        if($user === null) {
            $slice = new \PHPixie\Slice();
            $http  = new \PHPixie\HTTP($slice);
            $responses = $http->responses();
            return $responses->redirect('/auth');
        }
        //return $user->asObject();
        $components = $this->components();        
                
        //$checklistQuery = $components->orm()->query('user')->findOne(['checklists'])->asObject(true);
        //$userchecklistQuery = $components->orm()->query('checklist')->findOne(['users'])->asObject(true);        
        //->checklist()->asArray();
        
        /*$userchecklistQuery = $components->orm()->query('checklist')->relatedTo('users', function($userQuery){
            $userQuery->where('id', $user->id);
        })->find()->asArray(true);*/
        
        /**
         * All checklists
         */
        //$cheklistQuery = $components->orm()->query('checklist')->where('parentid', 0)->find()->asArray(true);
        $cheklistQuery = $components->orm()->query('checklist')->where('parentid', 0)->or('parentid', NULL)->find()->asArray(true);
        foreach($cheklistQuery as $parent){
            $chieldcheklistQuery = $components->orm()->query('checklist')->where('parentid', $parent->id)->find()->asArray(true);
            $parent->child = $chieldcheklistQuery;
        }
        
        //return $cheklistQuery;
        
        /**
         * Checked checklists
         */
        $userchecklistQuery = $components->orm()->query('checklist')->where('users.id', $user->id)->find()->asArray(true);
        $usercheck = array();
        foreach($userchecklistQuery as $element){            
            $usercheck[$element->id] = $element;
        }
        //return $usercheck;        
        /*return $usercheck;*/        
        return $components->template()->get('app:checklist', [
            'checklist' => $cheklistQuery,
            'usercheck' => $usercheck,
            'user'  => $this->user()
        ]);
    }

    /**
     * Post a checklist via AJAX
     *
     * @param Request $request HTTP request
     * @return mixed
     * @throws \Exception If the user is not logged in
     */
    public function postAction($request)
    {
        // Check if the user is logged in
        $user = $this->user();
        if($user === null) {
            throw new \Exception("User is not logged in");
        }
        
        //return $this->responses()->response('$request: '.print_r($request->data()->get(),true), [], 500);
        //return $request->data()->get()->asArray(true);
        foreach ($request->data()->get()['check'] as $checkid) {
            // Get the form and load it with data
            $form = $this->postForm();
            $form->submit(array('check' => $checkid));
            //return $this->responses()->response('validate: '.print_r(array('check' => (int)$checkid),true), [], 500);            
            // If the form is invalid echo the error from the 'text' field and
            // return a HTTP 500 status so that jQuery knows an error happened
            if(!$form->isValid()) {
                $userchecklist = $form->fieldError('check');
                return $this->responses()->response($userchecklist, [], 500);
            }
        }


        // Otherwise create a userchecklist
        /*$date = new \DateTime();
        $message = $this->components()->orm()->createEntity('message', [
            'text'   => $form->check,
            'date'   => $date->format('Y-m-d H:i:s'),
            'userId' => $user->id()
        ]);

        $message->save();*/
        $components = $this->components();
        $userchecklistQuery = $components->orm()->query('userchecklist')->where('userid', $user->id());
        //$count = $userchecklistQuery->count();
        $userchecklistQuery->delete();
        $checklists = array();
        foreach ($request->data()->get()['check'] as $checkid) {            
            $checklists = array(
                'checkid'   => $checkid,
                'userid'   => $user->id(),
                'status' => 1
            );
            $userchecklist = $this->components()->orm()->createEntity('userchecklist', $checklists);
            $userchecklist->save();
        }        
        // Return message entity as simple object.
        // It will automatically be converted into JSON
        //return $userchecklist->asObject(true);
        return $this->responses()->response('Записи внесены.', [], 200);
    }

    /**
     * New message form
     *
     * @return Form
     */
    protected function postForm()
    {
        $validate = $this->components()->validate();

        // Create a new validator
        $validator = $validate->validator();

        // We use a document validator (it's the one you'll be using the most)
        $document = $validator->rule()->addDocument();

        // Define a single field 'text'
        $document->valueField('check')
            // Mark field as required and specify the error text if its missing
            ->required("Please enter something")

            // Add a filter and specify the error message if it doesn't pass.
            // Note that each filter can have multiple rules.
            ->addFilter()
            ->numeric()
            ->message("checkid must be a number.");

        // Return a validator for this form
        return $validate->form($validator);
    }
}