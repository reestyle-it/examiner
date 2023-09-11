# Examiner

Let's you quickly examine, or rather evaluate, objects and/or datatypes. Depends primarily on
basic functions and Reflection classes to ensure speedy evaluations.

Please remember that, when working with objects, you should only be able to check for 
PUBLIC members. Trying to access private or protected members from outside an object
(except inherited) is pointless.

## Programming use cases
Examine if the provided `thing` is of given type and then call the argument given. If it's not 
of given type, either null will be returned or the given closure will be executed.

You can also surround it in a conditional block, but because it's already conditional, 
that's rarely useful.

Example:
```
examine(true)->whenBoolean(
    fn () => true,   // True block 
    fn () => false   // False block
);
```

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