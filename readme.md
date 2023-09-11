# Examiner

Let's you quickly examine, or rather evaluate, objects and/or datatypes. Depends primarily on
basic functions and Reflection classes to ensure speedy evaluations.

Please remember that, when working with objects, you should only be able to check for 
PUBLIC members. Trying to access private or protected members from outside an object
(except inherited) is pointless.

## Objects
```
$var = new SomeObject();
examine($var)->instanceOf(AbstractObject);
examine($var)->hasTrait(AbstractObject);
examine($var)->hasMethod('objectMethod');
examine($var)->hasProperty('objectProperty');
```

## Datatypes
### Booleans
```
examine(true)->isTrue(); // true
examine(' ')->couldBeTrue(); // true
examine('')->couldBeTrue(); // false
examine(1)->isTrue(); // false
examine(1)->couldBeTrue(); // true
```