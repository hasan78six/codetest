Following are the things which can help improve things over all code and 
according to me code looks average:
1. PHP Doc is not properly written throught the code nor detail is mentioned what function is all about.
2. Exception handling using try, catch and finally block is missing.
3. Logging should be added using report function.
4, Good thing is naming convention rules have been followed.
5. Under BaseRepository I would say that $model variable should be private as we 
set variable using constructor and get it using getModel function is BaseRepository.
6. Under BaseRepository $validationRules should be private and should be set using a function setValidationRules that funtion should be public.
7. Under BaseRepository $attributeNames varialble should be added with private access modifire as an array and setAttributeNames function should be added.
8. Under BaseRepository inside validatorAttributeNames function should return $attributeNames instead of an empty array.
9. Under BaseRepository try catch should be added inside all function just in case if model is null.
10. Under BaseRepository _validate function should be private as its being used undeer validate function only.
11. Under BaseRepository inside _validate function it will never throw an Exception as return false is there before exception so return false needs to be removed.
12. Under BaseRepository param \Illuminate\Validation\Validator should be imported in start.
13. Under BaseRepository inside findOrFail and findBySlug, ModelNotFoundException should be handle using try and catch.
14. Under BookingRepository variable $model is not required as its already there ub BaseRepository and could be accessed using get model function.
15, Under BookingRepository inside getUsersJobs function we should check $cuser is null under if condition.
16. Under BookingRepository inside getUsersJobsHistory function we should check $cuser is null under if condition.
17. Under BookingRepository inside getUsersJobsHistory function return should be outside if and else and for customer inside paginate function $pagenum variable should be passed.
18. Under BookingRepository inside getUsersJobsHistory function $totaljobs and $numpages should be outside if and elseif and normal and emergency jobs should be sorted using foreach loop.
19. Under BookingRepository inside store function over all function should be optimized and fixed and it has repeating conditions for immdiate jobs.
20. Under BookingRepository inside storeJobEmail function mailer email send should be done under event.
21. Under BookingRepository inside jobEnd function $job_detail name should be $job and check if $job is not null.


For more details I have refactored the code please check changelog.