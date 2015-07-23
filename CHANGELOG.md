# CHANGELOG

## develop branch

### New

* Checks\IsNull - check for NULLs
* Requirements\RequireNull - throws exception if data is not null

## 2.70 - Wed Jul 22 2015

### New

* Checks\IsNumeric - check for numeric data
* Requirements\RequireNumeric - throw exception if data is not numeric

## 2.6.2 - Sat Jul 18 2015

### Fixes

* ValueBuilders\FirstMethodMatchingType - no longer falls back to __invoke() on objects

## 2.6.1 - Sat Jul 18 2015

### Fixes

* ValueBuilders\FirstMethodMatchingType - did not strip the namespace from objects :(

## 2.6.0 - Sat Jul 18 2015

### New

* Checks\IsLogical - check for boolean data types
* Requirements\RequireLogical - throw exception if data is not Logical

## 2.5.2 - Sat Jul 18 2015

### Fixes

* ValueBuilders\FirstMethodMatchingType now takes a $eUnsupportedType parameter. This makes it possible for other libraries to get this value builder to throw their own E4xx_UnsupportedType exception (making it easier for callers to catch), instead of throwing exceptions defined inside php-reflection.

## 2.5.1 - Sat Jul 18 2015

### Fixes

* All requirements now take a $exception parameter. This makes it possible for other libraries to use these requirements to throw their own exceptions, instead of throwing exceptions from inside php-reflection.

## 2.5.0 - Sat Jul 18 2015

### New

* Requirements\RequireAssignable - throw exception if an item isn't an assignable type
* Requirements\RequireIndexable - throw exception if an item isn't an indexable type
* Requirements\RequireStringy - throw exception if an item isn't a stringy type
* Requirements\RequireTraversable - throw exception if an item isn't a traversable type

## 2.4.0 - Sat Jul 18 2015

### New

* Checks\IsStringy - is a variable a real string, or an object that can be used as a string?

## 2.3.0 - Fri Jul 10 2015

### New

* Checks\IsAssignable - does a variable support object-notation assignment (e.g. $a->$b)?
* Checks\IsIndexable - does a variable support array-notation assignment (e.g. $a[$b])?
* Checks\IsTraversable - can a variable be used in a foreach() loop?

### Fixes

* CodeCaller value builder is now implemented in `ganbarodigital/php-exceptions`
* E4xx_UnsupportedType uses the new UnsupportedType trait from `ganbarodigital/php-exceptions`

## 2.2.0 - Sat Jul 4 2015

### New

* CallableMethodsList value builder
* FirstMethodMatchingType value builder

## 2.1.1 - Sat Jun 27 2015

### Fix

* README: install v2, not v1!

## 2.1.0 - Sat Jun 27 2015

### New

* AllMatchingTypesList value builder
* CodeCaller value builer
* SimpleType value builder

## 2.0.0 - Wed Jun 24 2015

### Backwards Compatibility Breaks

* NamespaceFilter is now FilterNamespace

## 1.0.0 - Wed Jun 24 2015

Initial release.

### New

* NamespaceFilter added