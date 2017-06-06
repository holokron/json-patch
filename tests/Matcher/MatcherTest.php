<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests\Matcher;

use Holokron\JsonPatch\Definition\CompiledDefinition;
use Holokron\JsonPatch\Definition\Definition;
use Holokron\JsonPatch\Definition\DefinitionsCollection;
use Holokron\JsonPatch\Matcher\Matcher;
use Holokron\JsonPatch\Patch;
use PHPUnit\Framework\TestCase;
use Xpmock\TestCaseTrait;

/*
 * @author Michał Tęczyński <michal.teczynski@gmail.com>
 */
class MatcherTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var callable[]
     */
    private $callbacks;

    /**
     * @var DefinitionsCollection
     */
    private static $definitions;

    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->callbacks = [
            'add_user' => function () {
                return 'add_user';
            },
            'remove_user' => function () {
                return 'remove_user';
            },
            'replace_user' => function () {
                return 'replace_user';
            },
            'add_account' => function () {
                return 'add_account';
            },
        ];
    }

    protected function setUp()
    {
        $this->matcher = new Matcher($this->mockDefinitions());
    }

    /**
     * @dataProvider dataMatch
     */
    public function testMatch(Patch $patch, array $expected)
    {
        $result = $this->matcher->match($patch);

        $this->assertEquals($expected, $result);
        $this->assertInternalType('callable', $result[0]);
    }

    public function dataMatch(): array
    {
        return [
            [
                $this->mock(Patch::class, [
                    'getOp' => 'remove',
                    'getPath' => '/users/123',
                ]),
                [
                    $this->callbacks['remove_user'],
                    [
                        123,
                    ],
                ],
            ],
            [
                $this->mock(Patch::class, [
                    'getOp' => 'replace',
                    'getPath' => '/users/qwerty',
                ]),
                [
                    $this->callbacks['replace_user'],
                    [
                        'qwerty',
                    ],
                ],
            ],
            [
                $this->mock(Patch::class, [
                    'getOp' => 'add',
                    'getPath' => '/users',
                ]),
                [
                    $this->callbacks['add_user'],
                    [],
                ],
            ],
            [
                $this->mock(Patch::class, [
                    'getOp' => 'add',
                    'getPath' => '/accounts',
                ]),
                [
                    $this->callbacks['add_account'],
                    [],
                ],
            ],
        ];
    }

    private function mockDefinitions()
    {
        $definitions = new DefinitionsCollection();
        $definitions
            ->add('add_user', $this->mock(Definition::class, [
                'getOp' => 'add',
                'compile' => $this->mock(CompiledDefinition::class, [
                    'getRegex' => '/^\/users$/',
                    'getCallback' => $this->returnValue($this->callbacks['add_user']),
                ]),
            ]))
            ->add('remove_user', $this->mock(Definition::class, [
                'getOp' => 'remove',
                'compile' => $this->mock(CompiledDefinition::class, [
                    'getRegex' => '/^\/users\/([1-9]+\d*)$/',
                    'getCallback' => $this->returnValue($this->callbacks['remove_user']),
                ]),
            ]))
            ->add('replace_user', $this->mock(Definition::class, [
                'getOp' => 'replace',
                'compile' => $this->mock(CompiledDefinition::class, [
                    'getRegex' => '/^\/users\/(\w+)$/',
                    'getCallback' => $this->returnValue($this->callbacks['replace_user']),
                ]),
            ]))
            ->add('add_account', $this->mock(Definition::class, [
                'getOp' => 'add',
                'compile' => $this->mock(CompiledDefinition::class, [
                    'getRegex' => '/^\/accounts$/',
                    'getCallback' => $this->returnValue($this->callbacks['add_account']),
                ]),
            ]));

        return $definitions;
    }

    /**
     * @expectedException \Holokron\JsonPatch\Exception\NotMatchedException
     */
    public function testMatchWhenNoDefinitionsIsMatched()
    {
        $definitions = new DefinitionsCollection();
        $this->matcher->setDefinitions($definitions);
        $patch = $this->mock(Patch::class, null);
        $patch->mock()
            ->getOp('add');

        $this->matcher->match($patch);
    }
}
