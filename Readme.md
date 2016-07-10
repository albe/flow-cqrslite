# flow-cqrslite

A very simple extensively documented sample implementation of a (minimal) CQRS system with Neos Flow Framework (https://github.com/neos/flow).

For more information on CQRS in general, take a look at http://cqrs.nu/Faq, read Martin Fowlers bliki http://martinfowler.com/bliki/CQRS.html
or listen to Greg Young himself, the one who made up that name:
https://www.youtube.com/watch?v=JHGkaShoyNs (start in from 45:30 if you want to skip all the EventSourcing mambo-jambo)

In case you prefer a printable version of Greg's mind, you can also just look here: https://cqrs.files.wordpress.com/2010/11/cqrs_documents.pdf

## what are the costs?

CQRS inevitably adds some complexity to your code, as you have now two distinct models that were previously one and you need to make your commands (i.e. user intentions) explicit.

## what are the benefits?

- It is easier to reason about the system, since intentions to change the system are very explicit and adding new read models is easy without having to care about breaking the write side (containing your business rules).
- Your read side can be freely optimized to use cases/views, since you can easily denormalize write data.
- Also, it will be easy to scale the read and write side independently, so your business case can safely grow later on to bigger user numbers.
- As a bonus, you get a very easy command log that keeps track of all state changing requests done to your system, helping you track (and reproduce) buggy behaviour
or creating the most capable smoke-test for your system, by replaying commands that have already been handled and verifying functionality and possibly the final state.

## where to go from here?

This is a very minimal CQRS implementation and only really separates the two models, but does not yet separate the persistence storage behind the two.

The next step would be to use different database connections (or whole databases) for the write- and read-side and then to fully seperate the two sides on
different systems and add async communication, fully embracing Eventual Consistency.