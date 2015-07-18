# CHANGELOG

## develop branch

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