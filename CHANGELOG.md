# CHANGELOG

## develop branch

### Fixes

* IsCompatibleWith - performance improvements
* AllMatchingTypesListTest - update tests to check for more accurate error messages from latest Exceptions release

## 2.12.1 - Thu Sep 17 2015

### Fixes

* AllMatchingTypesList - treats `stdClass` as a Traversable

## 2.12.0 - Wed Sep 16 2015

### Deprecated

* FirstMethodMatchingType::from() - convert your classes to use the much faster LookupMethodByType instead

### New

* Added `ValueBuilders\ConvertToString` - a reusable way to convert anything into a string
* Added `ValueBuilders\LookupMethodByType` - a much faster alternative to FirstMethodMatchingType

### Fix

* AllMatchingTypesList - when given an object, specialist types such as "String" and "Callable" now appear ahead of "Object" in the list
* AllMatchingTypesList - now detects "Callable" strings

## 2.11.0 - Wed Sep 9 2015

### New

* Added Checks\IsArray
* Added Checks\IsCallable
* Added Checks\IsCompatibleWith
* Added Checks\IsDefinedClass
* Added Checks\IsDefinedInterface
* Added Checks\IsDefinedTrait
* Added Checks\IsDefinedObjectType
* Added Checks\IsObject
* Added Requirements\RequireArray
* Added Requirements\RequireCallable
* Added Requirements\RequireDefinedClass
* Added Requirements\RequireDefinedInterface
* Added Requirements\RequireDefinedObjectType
* Added Requirements\RequireDefinedTrait
* Added Requirements\RequireObject

### Fixes

* Requirements\Require* - now reports the correct type when throwing E4xx_UnsupportedType
* ValueBuilders\AllMatchingTypesList::fromClass() - now accepts interfaces

## 2.10.0 - Fri Sep 4 2015

### New

* static::xxxMixed() is now __deprecated__
* use the static:xxx() (e.g. static::check() and static::from()) instead

## 2.9.0 - Wed Sep 2 2015

### New

* Checks\IsPcreRegex - check if a string is a valid PCRE regex
* Exceptions\E4xx_InvalidPcreRegex - exception for when we have an invalid PCRE regex
* Requirements\RequirePcreRegex - throws exception if data is not a valid PCRE regex

## 2.8.0 - Thu Jul 23 2015

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