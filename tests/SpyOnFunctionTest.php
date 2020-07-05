<?php

use Verde\SpyOnFunction;

use function Verde\expect;
use function Verde\spyOn;
use function Verde\Test\doSomething;
use function Verde\Test\dummyTest;
use function Verde\Test\fails;

require_once __DIR__ . "/TestUtils/Utils.php";

//SpyOnFunction
 it(
     'returns an instance of spy class',
        function () {
            $spy = spyOn('Verde\Test\dummyTest');

            expect($spy)->toBeInstanceOf(SpyOnFunction::class);
        }
 );

// SpyOn
 it(
     'renames the original function',
     function () {
         spyOn('Verde\Test\dummyTest');

         expect(function_exists(
         '___verde_php_verde_test_dummytest'
         ))->toBeTrue();
     }
 );

 it(
     'returns the same instance when spying on the same function',
     function () {
         $spy1 = spyOn('Verde\Test\testSpy');
         $spy2 = spyOn('Verde\Test\testSpy');

         expect($spy1)->toBe($spy2);
     }
 );

 it(
     'spies on the function call',
     function () {
         $spy = spyOn('Verde\Test\dummyTest');

         dummyTest();

         expect($spy)->toHaveBeenCalledWith();

         fails(function () use ($spy) {
             expect($spy)->not()->toHaveBeenCalled();
         });
     }
 );

 it('executes the original function', function () {
     spyOn('Verde\Test\dummyTest');

     $testValue = 89;
     $result = dummyTest($testValue);

     expect($result)->toBe((string) $testValue);
 });

 it('resets the function invocation', function () {
     $spy = spyOn('Verde\Test\dummyTest');

     dummyTest();
     $spy->mockClear();

     expect($spy)->not()->toHaveBeenCalled();

     fails(function () use ($spy) {
         expect($spy)->toHaveBeenCalled();
     });
 });

 it('spies on a function invocation even if not called directly', function () {
     $spy = spyOn('Verde\Test\dummyTest');
     $spy->mockClear();

     doSomething();
     expect($spy)->toHaveBeenCalledWith();

     fails(function () use ($spy) {
         expect($spy)->not()->toHaveBeenCalled();
     });
 });

 it('mocks the original implementation', function () {
     $message = 'yay, it works!';
     $spy = spyOn('Verde\Test\dummyTest');

     $spy->mockImplementation(function () use ($message) {
         return $message;
     });

     expect(dummyTest())->toBe($message);
 });

 it('restores the original function', function () {
     $message = 'yay, it works!';
     $spy = spyOn('Verde\Test\dummyTest');

     $spy->mockClear();
     $spy->mockImplementation(function () use ($message) {
         return $message;
     });

     expect(dummyTest())->toBe($message);

     $spy->mockImplementationOnce(function () {
         return 'wowowow';
     });
     $spy->mockRestore();

     expect(dummyTest())->toBe(42);

     $result = dummyTest('ciao');
     expect($result)->toBe('ciao');

     // We're still able to spy on the calls
     expect($spy)->toHaveBeenCalledWith('ciao');

     expect(dummyTest(123, 'stella'))->toBe("123, stella");

     expect($spy)->toHaveBeenCalledTimes(3);
 });
