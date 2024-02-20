# Examiner

Let's you quickly examine, or rather evaluate, objects and/or datatypes. Depends primarily on
basic functions and Reflection classes to ensure speedy evaluations.

Please remember that, when working with objects, you should only be able to check for 
PUBLIC members. Trying to access private or protected members from outside an object
(except inherited) is pointless.

## Programming use cases
Examine if the provided `thing` is of given type and then call the argument given. If it's not 
of given type, either null will be returned or the given closure will be executed.

Examples:
## When
```
$bool = true;
examine($bool)->whenBoolean(
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
$bool = true;
examine($bool)->isTrue(); // true
examine($bool)->isFalse(); // false (duh)
examine($bool)->isEmpty(); // MethodNotFoundException
examine($bool)->ignoreType()->isEmpty(); // null
```

### Strings
```
examine(' ')->couldBeTrue(); // true
examine('')->couldBeTrue(); // false
examine(0)->couldBeTrue(); // false
examine(0)->couldBeFalse(); // true
examine(' ')->isEmpty(); // false
examine('')->isEmpty(); // true
examine(null)->isEmpty(); // MethodNotFoundException
examine(0)->couldBeTrue(); // false
examine(0)->couldBeFalse(); // true
```

### Integers & floats
```
examine(1)->isInt(); // true
examine(1)->isFloat(); // false
examine(1.0)->isFloat(); // true
examine(1.0)->isInt(); // false
examine(1)->isTrue(); // false
examine(1)->couldBeTrue(); // true

examine(1.0)->whenFloat(fn () => 13.45);
```