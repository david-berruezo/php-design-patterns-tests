<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * =============================================
 * ChainOfResponsibility - Conceptual Tests
 * =============================================
 */
class ChainOfResponsibilityTest extends TestCase
{
    public function testMonkeyHandlesBanana(): void
    {
        $monkey = new \RefactoringGuru\ChainOfResponsibility\Conceptual\MonkeyHandler();

        $result = $monkey->handle('Banana');

        $this->assertStringContainsString('Monkey', $result);
        $this->assertStringContainsString('Banana', $result);
    }

    public function testSquirrelHandlesNut(): void
    {
        $squirrel = new \RefactoringGuru\ChainOfResponsibility\Conceptual\SquirrelHandler();

        $result = $squirrel->handle('Nut');

        $this->assertStringContainsString('Squirrel', $result);
    }

    public function testDogHandlesMeatBall(): void
    {
        $dog = new \RefactoringGuru\ChainOfResponsibility\Conceptual\DogHandler();

        $result = $dog->handle('MeatBall');

        $this->assertStringContainsString('Dog', $result);
    }

    public function testChainPassesRequestForward(): void
    {
        $monkey = new \RefactoringGuru\ChainOfResponsibility\Conceptual\MonkeyHandler();
        $squirrel = new \RefactoringGuru\ChainOfResponsibility\Conceptual\SquirrelHandler();
        $monkey->setNext($squirrel);

        $result = $monkey->handle('Nut');

        $this->assertStringContainsString('Squirrel', $result);
    }

    public function testUnhandledRequestReturnsNull(): void
    {
        $monkey = new \RefactoringGuru\ChainOfResponsibility\Conceptual\MonkeyHandler();

        $result = $monkey->handle('Pizza');

        $this->assertNull($result);
    }

    public function testFullChain(): void
    {
        $monkey = new \RefactoringGuru\ChainOfResponsibility\Conceptual\MonkeyHandler();
        $squirrel = new \RefactoringGuru\ChainOfResponsibility\Conceptual\SquirrelHandler();
        $dog = new \RefactoringGuru\ChainOfResponsibility\Conceptual\DogHandler();

        $monkey->setNext($squirrel)->setNext($dog);

        $this->assertStringContainsString('Monkey', $monkey->handle('Banana'));
        $this->assertStringContainsString('Squirrel', $monkey->handle('Nut'));
        $this->assertStringContainsString('Dog', $monkey->handle('MeatBall'));
        $this->assertNull($monkey->handle('Coffee'));
    }
}

/**
 * =============================================
 * Command - Conceptual Tests
 * =============================================
 */
class CommandConceptualTest extends TestCase
{
    public function testSimpleCommandExecutes(): void
    {
        $command = new \RefactoringGuru\Command\Conceptual\SimpleCommand('Hello');

        $this->expectOutputRegex('/Hello/');
        $command->execute();
    }

    public function testComplexCommandDelegatesToReceiver(): void
    {
        $receiver = new \RefactoringGuru\Command\Conceptual\Receiver();
        $command = new \RefactoringGuru\Command\Conceptual\ComplexCommand($receiver, 'a', 'b');

        $this->expectOutputRegex('/Working on \(a/');
        $command->execute();
    }

    public function testInvokerExecutesCommands(): void
    {
        $invoker = new \RefactoringGuru\Command\Conceptual\Invoker();
        $invoker->setOnStart(new \RefactoringGuru\Command\Conceptual\SimpleCommand('Start'));

        $this->expectOutputRegex('/Start/');
        $invoker->doSomethingImportant();
    }
}

/**
 * =============================================
 * Composite - Conceptual Tests
 * =============================================
 */
class CompositeConceptualTest extends TestCase
{
    public function testLeafOperation(): void
    {
        $leaf = new \RefactoringGuru\Composite\Conceptual\Leaf();

        $this->assertSame('Leaf', $leaf->operation());
        $this->assertFalse($leaf->isComposite());
    }

    public function testCompositeWithChildren(): void
    {
        $composite = new \RefactoringGuru\Composite\Conceptual\Composite();
        $composite->add(new \RefactoringGuru\Composite\Conceptual\Leaf());
        $composite->add(new \RefactoringGuru\Composite\Conceptual\Leaf());

        $result = $composite->operation();

        $this->assertSame('Branch(Leaf+Leaf)', $result);
        $this->assertTrue($composite->isComposite());
    }

    public function testNestedComposite(): void
    {
        $tree = new \RefactoringGuru\Composite\Conceptual\Composite();
        $branch = new \RefactoringGuru\Composite\Conceptual\Composite();
        $branch->add(new \RefactoringGuru\Composite\Conceptual\Leaf());
        $tree->add($branch);
        $tree->add(new \RefactoringGuru\Composite\Conceptual\Leaf());

        $result = $tree->operation();

        $this->assertSame('Branch(Branch(Leaf)+Leaf)', $result);
    }

    public function testRemoveChild(): void
    {
        $composite = new \RefactoringGuru\Composite\Conceptual\Composite();
        $leaf = new \RefactoringGuru\Composite\Conceptual\Leaf();
        $composite->add($leaf);
        $composite->add(new \RefactoringGuru\Composite\Conceptual\Leaf());

        $composite->remove($leaf);
        $result = $composite->operation();

        $this->assertSame('Branch(Leaf)', $result);
    }
}

/**
 * =============================================
 * Composite - RealWorld Tests (HTML Form)
 * =============================================
 */
class CompositeRealWorldTest extends TestCase
{
    public function testInputRender(): void
    {
        $input = new \RefactoringGuru\Composite\RealWorld\Input('email', 'Email', 'text');
        $input->setData('test@example.com');

        $html = $input->render();

        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringContainsString('test@example.com', $html);
    }

    public function testFieldsetRender(): void
    {
        $fieldset = new \RefactoringGuru\Composite\RealWorld\Fieldset('info', 'Info');
        $fieldset->add(new \RefactoringGuru\Composite\RealWorld\Input('name', 'Name', 'text'));

        $html = $fieldset->render();

        $this->assertStringContainsString('<fieldset>', $html);
        $this->assertStringContainsString('<legend>Info</legend>', $html);
        $this->assertStringContainsString('name="name"', $html);
    }

    public function testFormRender(): void
    {
        $form = new \RefactoringGuru\Composite\RealWorld\Form('product', 'Add Product', '/add');
        $form->add(new \RefactoringGuru\Composite\RealWorld\Input('title', 'Title', 'text'));

        $html = $form->render();

        $this->assertStringContainsString('<form action="/add">', $html);
        $this->assertStringContainsString('Add Product', $html);
    }
}

/**
 * =============================================
 * Decorator - Conceptual Tests
 * =============================================
 */
class DecoratorConceptualTest extends TestCase
{
    public function testConcreteComponent(): void
    {
        $component = new \RefactoringGuru\Decorator\Conceptual\ConcreteComponent();

        $this->assertSame('ConcreteComponent', $component->operation());
    }

    public function testDecoratorA(): void
    {
        $component = new \RefactoringGuru\Decorator\Conceptual\ConcreteComponent();
        $decoratorA = new \RefactoringGuru\Decorator\Conceptual\ConcreteDecoratorA($component);

        $this->assertSame('ConcreteDecoratorA(ConcreteComponent)', $decoratorA->operation());
    }

    public function testDecoratorB(): void
    {
        $component = new \RefactoringGuru\Decorator\Conceptual\ConcreteComponent();
        $decoratorB = new \RefactoringGuru\Decorator\Conceptual\ConcreteDecoratorB($component);

        $this->assertSame('ConcreteDecoratorB(ConcreteComponent)', $decoratorB->operation());
    }

    public function testStackedDecorators(): void
    {
        $component = new \RefactoringGuru\Decorator\Conceptual\ConcreteComponent();
        $decoratorA = new \RefactoringGuru\Decorator\Conceptual\ConcreteDecoratorA($component);
        $decoratorB = new \RefactoringGuru\Decorator\Conceptual\ConcreteDecoratorB($decoratorA);

        $this->assertSame(
            'ConcreteDecoratorB(ConcreteDecoratorA(ConcreteComponent))',
            $decoratorB->operation()
        );
    }
}

/**
 * =============================================
 * Decorator - RealWorld Tests (Text Filtering)
 * =============================================
 */
class DecoratorRealWorldTest extends TestCase
{
    public function testTextInputReturnsRawText(): void
    {
        $input = new \RefactoringGuru\Decorator\RealWorld\TextInput();

        $this->assertSame('Hello World', $input->formatText('Hello World'));
    }

    public function testPlainTextFilterStripsAllTags(): void
    {
        $input = new \RefactoringGuru\Decorator\RealWorld\TextInput();
        $filter = new \RefactoringGuru\Decorator\RealWorld\PlainTextFilter($input);

        $result = $filter->formatText('<b>Bold</b> text');

        $this->assertSame('Bold text', $result);
    }

    public function testDangerousHTMLTagsFilterRemovesScript(): void
    {
        $input = new \RefactoringGuru\Decorator\RealWorld\TextInput();
        $filter = new \RefactoringGuru\Decorator\RealWorld\DangerousHTMLTagsFilter($input);

        $result = $filter->formatText('Hello <script>alert("xss")</script> World');

        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('Hello', $result);
        $this->assertStringContainsString('World', $result);
    }

    public function testMarkdownFormatConvertsHeaders(): void
    {
        $input = new \RefactoringGuru\Decorator\RealWorld\TextInput();
        $markdown = new \RefactoringGuru\Decorator\RealWorld\MarkdownFormat($input);

        $result = $markdown->formatText('# Title');

        $this->assertStringContainsString('<h1>Title</h1>', $result);
    }

    public function testStackedMarkdownAndFilter(): void
    {
        $input = new \RefactoringGuru\Decorator\RealWorld\TextInput();
        $markdown = new \RefactoringGuru\Decorator\RealWorld\MarkdownFormat($input);
        $filter = new \RefactoringGuru\Decorator\RealWorld\DangerousHTMLTagsFilter($markdown);

        $text = "# Welcome\n\n**Bold** text\n\n<script>xss</script>";
        $result = $filter->formatText($text);

        $this->assertStringContainsString('<h1>Welcome</h1>', $result);
        $this->assertStringContainsString('<strong>Bold</strong>', $result);
        $this->assertStringNotContainsString('<script>', $result);
    }
}

/**
 * =============================================
 * Facade - Conceptual Tests
 * =============================================
 */
class FacadeConceptualTest extends TestCase
{
    public function testFacadeOperation(): void
    {
        $facade = new \RefactoringGuru\Facade\Conceptual\Facade();

        $result = $facade->operation();

        $this->assertStringContainsString('Subsystem1: Ready!', $result);
        $this->assertStringContainsString('Subsystem2: Get ready!', $result);
        $this->assertStringContainsString('Subsystem1: Go!', $result);
        $this->assertStringContainsString('Subsystem2: Fire!', $result);
    }

    public function testFacadeWithExistingSubsystems(): void
    {
        $sub1 = new \RefactoringGuru\Facade\Conceptual\Subsystem1();
        $sub2 = new \RefactoringGuru\Facade\Conceptual\Subsystem2();
        $facade = new \RefactoringGuru\Facade\Conceptual\Facade($sub1, $sub2);

        $result = $facade->operation();

        $this->assertStringContainsString('Facade initializes', $result);
    }
}

/**
 * =============================================
 * Factory Method - Conceptual Tests
 * =============================================
 */
class FactoryMethodConceptualTest extends TestCase
{
    public function testConcreteCreator1(): void
    {
        $creator = new \RefactoringGuru\FactoryMethod\Conceptual\ConcreteCreator1();

        ob_start();
        $result = $creator->someOperation();
        ob_end_clean();

        $this->assertStringContainsString('ConcreteProduct1', $result);
    }

    public function testConcreteCreator2(): void
    {
        $creator = new \RefactoringGuru\FactoryMethod\Conceptual\ConcreteCreator2();

        ob_start();
        $result = $creator->someOperation();
        ob_end_clean();

        $this->assertStringContainsString('ConcreteProduct2', $result);
    }
}

/**
 * =============================================
 * Flyweight - Conceptual Tests
 * =============================================
 */
class FlyweightConceptualTest extends TestCase
{
    public function testFlyweightOperation(): void
    {
        $flyweight = new \RefactoringGuru\Flyweight\Conceptual\Flyweight(['brand' => 'Toyota']);

        $this->expectOutputRegex('/Toyota/');
        $flyweight->operation(['owner' => 'David']);
    }

    public function testFlyweightFactoryReusesInstances(): void
    {
        $factory = new \RefactoringGuru\Flyweight\Conceptual\FlyweightFactory([
            ['Toyota', 'Camry', 'white'],
            ['BMW', 'X5', 'black'],
        ]);

        ob_start();
        $factory->listFlyweights();
        $output = ob_get_clean();

        $this->assertStringContainsString('2', $output); // 2 flyweights
    }
}

/**
 * =============================================
 * Proxy - Conceptual Tests
 * =============================================
 */
class ProxyConceptualTest extends TestCase
{
    public function testRealSubjectRequest(): void
    {
        $subject = new \RefactoringGuru\Proxy\Conceptual\RealSubject();

        $this->expectOutputString("RealSubject: Handling request.\n");
        $subject->request();
    }

    public function testProxyDelegatesToRealSubject(): void
    {
        $realSubject = new \RefactoringGuru\Proxy\Conceptual\RealSubject();
        $proxy = new \RefactoringGuru\Proxy\Conceptual\Proxy($realSubject);

        $this->expectOutputRegex('/RealSubject: Handling request/');
        $proxy->request();
    }

    public function testProxyLogsAccess(): void
    {
        $realSubject = new \RefactoringGuru\Proxy\Conceptual\RealSubject();
        $proxy = new \RefactoringGuru\Proxy\Conceptual\Proxy($realSubject);

        $this->expectOutputRegex('/Proxy: Logging the time of request/');
        $proxy->request();
    }
}

/**
 * =============================================
 * Singleton - Conceptual Tests
 * =============================================
 */
class SingletonConceptualTest extends TestCase
{
    public function testSingletonReturnsSameInstance(): void
    {
        $instance1 = \RefactoringGuru\Singleton\Conceptual\Singleton::getInstance();
        $instance2 = \RefactoringGuru\Singleton\Conceptual\Singleton::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function testSingletonCannotBeUnserialized(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot unserialize a singleton.');

        $instance = \RefactoringGuru\Singleton\Conceptual\Singleton::getInstance();
        $instance->__wakeup();
    }
}

/**
 * =============================================
 * Strategy - Conceptual Tests
 * =============================================
 */
class StrategyConceptualTest extends TestCase
{
    public function testConcreteStrategyASortsAscending(): void
    {
        $strategy = new \RefactoringGuru\Strategy\Conceptual\ConcreteStrategyA();
        $result = $strategy->doAlgorithm(['c', 'a', 'b']);

        $this->assertSame(['a', 'b', 'c'], $result);
    }

    public function testConcreteStrategyBSortsDescending(): void
    {
        $strategy = new \RefactoringGuru\Strategy\Conceptual\ConcreteStrategyB();
        $result = $strategy->doAlgorithm(['a', 'c', 'b']);

        $this->assertSame(['c', 'b', 'a'], $result);
    }

    public function testContextUsesStrategy(): void
    {
        $strategy = new \RefactoringGuru\Strategy\Conceptual\ConcreteStrategyA();
        $context = new \RefactoringGuru\Strategy\Conceptual\Context($strategy);

        $this->expectOutputRegex('/a,b,c,d,e/');
        $context->doSomeBusinessLogic();
    }

    public function testContextCanSwitchStrategy(): void
    {
        $context = new \RefactoringGuru\Strategy\Conceptual\Context(
            new \RefactoringGuru\Strategy\Conceptual\ConcreteStrategyA()
        );

        $context->setStrategy(new \RefactoringGuru\Strategy\Conceptual\ConcreteStrategyB());

        $this->expectOutputRegex('/e,d,c,b,a/');
        $context->doSomeBusinessLogic();
    }
}

/**
 * =============================================
 * Interpreter - RealWorld Tests
 * =============================================
 */
class InterpreterTest extends TestCase
{
    public function testVariableExpression(): void
    {
        $context = new \RefactoringGuru\Interpreter\RealWorld\Context();
        $x = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('x');
        $context->assign($x, true);

        $this->assertTrue($x->interpret($context));
    }

    public function testAndExpression(): void
    {
        $context = new \RefactoringGuru\Interpreter\RealWorld\Context();
        $x = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('x');
        $y = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('y');
        $context->assign($x, true);
        $context->assign($y, true);

        $andExp = new \RefactoringGuru\Interpreter\RealWorld\AndExp($x, $y);

        $this->assertTrue($andExp->interpret($context));
    }

    public function testAndExpressionFalse(): void
    {
        $context = new \RefactoringGuru\Interpreter\RealWorld\Context();
        $x = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('x');
        $y = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('y');
        $context->assign($x, true);
        $context->assign($y, false);

        $andExp = new \RefactoringGuru\Interpreter\RealWorld\AndExp($x, $y);

        $this->assertFalse($andExp->interpret($context));
    }

    public function testOrExpression(): void
    {
        $context = new \RefactoringGuru\Interpreter\RealWorld\Context();
        $x = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('x');
        $y = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('y');
        $context->assign($x, false);
        $context->assign($y, true);

        $orExp = new \RefactoringGuru\Interpreter\RealWorld\OrExp($x, $y);

        $this->assertTrue($orExp->interpret($context));
    }

    public function testOrExpressionBothFalse(): void
    {
        $context = new \RefactoringGuru\Interpreter\RealWorld\Context();
        $x = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('x');
        $y = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('y');
        $context->assign($x, false);
        $context->assign($y, false);

        $orExp = new \RefactoringGuru\Interpreter\RealWorld\OrExp($x, $y);

        $this->assertFalse($orExp->interpret($context));
    }

    public function testComplexExpression(): void
    {
        $context = new \RefactoringGuru\Interpreter\RealWorld\Context();
        $x = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('x');
        $y = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('y');
        $z = new \RefactoringGuru\Interpreter\RealWorld\VariableExp('z');

        $context->assign($x, true);
        $context->assign($y, false);
        $context->assign($z, true);

        // (x AND y) OR z => (true AND false) OR true => false OR true => true
        $expression = new \RefactoringGuru\Interpreter\RealWorld\OrExp(
            new \RefactoringGuru\Interpreter\RealWorld\AndExp($x, $y),
            $z
        );

        $this->assertTrue($expression->interpret($context));
    }
}

/**
 * =============================================
 * Mediator - Conceptual Tests
 * =============================================
 */
class MediatorConceptualTest extends TestCase
{
    public function testMediatorReactsOnEventA(): void
    {
        $c1 = new \RefactoringGuru\Mediator\Conceptual\Component1();
        $c2 = new \RefactoringGuru\Mediator\Conceptual\Component2();
        new \RefactoringGuru\Mediator\Conceptual\ConcreteMediator($c1, $c2);

        $this->expectOutputRegex('/Mediator reacts on A/');
        $c1->doA();
    }

    public function testMediatorReactsOnEventD(): void
    {
        $c1 = new \RefactoringGuru\Mediator\Conceptual\Component1();
        $c2 = new \RefactoringGuru\Mediator\Conceptual\Component2();
        new \RefactoringGuru\Mediator\Conceptual\ConcreteMediator($c1, $c2);

        $this->expectOutputRegex('/Mediator reacts on D/');
        $c2->doD();
    }
}

/**
 * =============================================
 * Visitor - Conceptual Tests
 * =============================================
 */
class VisitorConceptualTest extends TestCase
{
    public function testConcreteVisitor1VisitsComponentA(): void
    {
        $componentA = new \RefactoringGuru\Visitor\Conceptual\ConcreteComponentA();
        $visitor1 = new \RefactoringGuru\Visitor\Conceptual\ConcreteVisitor1();

        $this->expectOutputRegex('/A \+ ConcreteVisitor1/');
        $componentA->accept($visitor1);
    }

    public function testConcreteVisitor2VisitsComponentB(): void
    {
        $componentB = new \RefactoringGuru\Visitor\Conceptual\ConcreteComponentB();
        $visitor2 = new \RefactoringGuru\Visitor\Conceptual\ConcreteVisitor2();

        $this->expectOutputRegex('/B \+ ConcreteVisitor2/');
        $componentB->accept($visitor2);
    }

    public function testComponentAExclusiveMethod(): void
    {
        $component = new \RefactoringGuru\Visitor\Conceptual\ConcreteComponentA();
        $this->assertSame('A', $component->exclusiveMethodOfConcreteComponentA());
    }

    public function testComponentBSpecialMethod(): void
    {
        $component = new \RefactoringGuru\Visitor\Conceptual\ConcreteComponentB();
        $this->assertSame('B', $component->specialMethodOfConcreteComponentB());
    }
}

/**
 * =============================================
 * Memento - Conceptual Tests
 * =============================================
 */
class MementoConceptualTest extends TestCase
{
    public function testOriginatorSavesState(): void
    {
        ob_start();
        $originator = new \RefactoringGuru\Memento\Conceptual\Originator('initial');
        ob_end_clean();

        $memento = $originator->save();

        $this->assertInstanceOf(\RefactoringGuru\Memento\Conceptual\Memento::class, $memento);
        $this->assertNotEmpty($memento->getName());
        $this->assertNotEmpty($memento->getDate());
    }

    public function testCaretakerManagesHistory(): void
    {
        ob_start();
        $originator = new \RefactoringGuru\Memento\Conceptual\Originator('state1');
        $caretaker = new \RefactoringGuru\Memento\Conceptual\Caretaker($originator);

        $caretaker->backup();
        $originator->doSomething();
        $caretaker->backup();
        $originator->doSomething();

        // Undo should restore a previous state
        $caretaker->undo();
        $output = ob_get_clean();

        $this->assertStringContainsString('Caretaker: Restoring state', $output);
    }
}

/**
 * =============================================
 * State - Conceptual Tests
 * =============================================
 */
class StateConceptualTest extends TestCase
{
    public function testContextTransitionsState(): void
    {
        $this->expectOutputRegex('/Transition to/');

        $context = new \RefactoringGuru\State\Conceptual\Context(
            new \RefactoringGuru\State\Conceptual\ConcreteStateA()
        );
        $context->request1();
    }

    public function testStateTransitionOnRequest(): void
    {
        ob_start();
        $context = new \RefactoringGuru\State\Conceptual\Context(
            new \RefactoringGuru\State\Conceptual\ConcreteStateA()
        );
        $context->request1();
        $output = ob_get_clean();

        $this->assertStringContainsString('ConcreteStateA handles request1', $output);
        $this->assertStringContainsString('ConcreteStateB', $output);
    }
}

/**
 * =============================================
 * TemplateMethod - Conceptual Tests
 * =============================================
 */
class TemplateMethodConceptualTest extends TestCase
{
    public function testConcreteClass1(): void
    {
        $class = new \RefactoringGuru\TemplateMethod\Conceptual\ConcreteClass1();

        ob_start();
        $class->templateMethod();
        $output = ob_get_clean();

        $this->assertStringContainsString('AbstractClass says: I am doing the bulk of the work', $output);
        $this->assertStringContainsString('ConcreteClass1 says: Implemented Operation1', $output);
        $this->assertStringContainsString('ConcreteClass1 says: Implemented Operation2', $output);
    }

    public function testConcreteClass2(): void
    {
        $class = new \RefactoringGuru\TemplateMethod\Conceptual\ConcreteClass2();

        ob_start();
        $class->templateMethod();
        $output = ob_get_clean();

        $this->assertStringContainsString('ConcreteClass2 says: Implemented Operation1', $output);
        $this->assertStringContainsString('ConcreteClass2 says: Overridden Hook1', $output);
    }
}

/**
 * =============================================
 * Iterator - Conceptual Tests
 * =============================================
 */
class IteratorConceptualTest extends TestCase
{
    public function testAlphabeticalOrderIterator(): void
    {
        $collection = new \RefactoringGuru\Iterator\Conceptual\WordsCollection();
        $collection->addItem('First');
        $collection->addItem('Second');
        $collection->addItem('Third');

        $items = [];
        foreach ($collection->getIterator() as $item) {
            $items[] = $item;
        }

        $this->assertSame(['First', 'Second', 'Third'], $items);
    }

    public function testReverseOrderIterator(): void
    {
        $collection = new \RefactoringGuru\Iterator\Conceptual\WordsCollection();
        $collection->addItem('First');
        $collection->addItem('Second');
        $collection->addItem('Third');

        $items = [];
        foreach ($collection->getReverseIterator() as $item) {
            $items[] = $item;
        }

        $this->assertSame(['Third', 'Second', 'First'], $items);
    }
}

/**
 * =============================================
 * Observer - Conceptual Tests
 * =============================================
 */
class ObserverConceptualTest extends TestCase
{
    public function testSubjectNotifiesObservers(): void
    {
        $subject = new \RefactoringGuru\Observer\Conceptual\Subject();
        $observerA = new \RefactoringGuru\Observer\Conceptual\ConcreteObserverA();

        $subject->attach($observerA);

        // Set state directly to < 3 so ConcreteObserverA reacts deterministically
        $subject->state = 1;
        ob_start();
        $subject->notify();
        $output = ob_get_clean();

        $this->assertStringContainsString('ConcreteObserverA: Reacted', $output);
    }

    public function testObserverBReactsToHighState(): void
    {
        $subject = new \RefactoringGuru\Observer\Conceptual\Subject();
        $observerB = new \RefactoringGuru\Observer\Conceptual\ConcreteObserverB();

        $subject->attach($observerB);

        $subject->state = 5;
        ob_start();
        $subject->notify();
        $output = ob_get_clean();

        $this->assertStringContainsString('ConcreteObserverB: Reacted', $output);
    }

    public function testObserverADoesNotReactToHighState(): void
    {
        $subject = new \RefactoringGuru\Observer\Conceptual\Subject();
        $observerA = new \RefactoringGuru\Observer\Conceptual\ConcreteObserverA();

        $subject->attach($observerA);

        $subject->state = 5;
        ob_start();
        $subject->notify();
        $output = ob_get_clean();

        $this->assertStringNotContainsString('ConcreteObserverA: Reacted', $output);
    }

    public function testDetachedObserverIsNotNotified(): void
    {
        $subject = new \RefactoringGuru\Observer\Conceptual\Subject();
        $observerA = new \RefactoringGuru\Observer\Conceptual\ConcreteObserverA();

        $subject->attach($observerA);
        $subject->detach($observerA);

        ob_start();
        $subject->someBusinessLogic();
        $output = ob_get_clean();

        $this->assertStringNotContainsString('ConcreteObserverA: Reacted', $output);
    }
}
