Andrew has tried where possible to have both characters and strings, so string tests are really SQL injections, CSS injections, LDAP injections, etc. If you add more tests, please try to do this as well.


Future work - there are NO abuse cases. There should be a null test. There should be a overlong encoding test wherever possible. etc, etc. Each codec author needs to consider adding more tests. In addition, some of the tests, notably the HTML codec have more than one test in per test method. This should be split out.


Lastly - the codec author MUST update the test suite expected results. Some tests are probably the correct values, but we MUST be absolutely 100% compliant with the J2EE reference implementation, so we can interoperate with any other ESAPI implementation via Ajax / RPC. So I've put in **some** of the expected test results, but they could be wrong. We need to verify what the J2EE reference implementation does and ensure that we are either bug compatible or indeed totally compatible with their results.