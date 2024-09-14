import eslint from '@eslint/js';
import globals from 'globals';
import stylistic from '@stylistic/eslint-plugin';
import reacteslint from 'eslint-plugin-react';

export default [

    // Base config
    eslint.configs.recommended,
    stylistic.configs['recommended-flat'],
    reacteslint.configs.flat.recommended,

    {
        settings: {
            react: {
                version: '18.3.1',
            },
        },

        languageOptions: {
            globals: {
                ...globals.browser,
                'RPBChessboard': 'writable',
                'wp': 'readonly',
            },
        },

        rules: {

            // Style
            '@stylistic/array-bracket-spacing': [ 'error', 'always' ],
            '@stylistic/arrow-parens': [ 'error', 'as-needed' ],
            '@stylistic/indent': [ 'error', 4, { SwitchCase: 1 } ],
            '@stylistic/indent-binary-ops': [ 'error', 4 ],
            '@stylistic/linebreak-style': [ 'error', 'unix' ],
            '@stylistic/max-statements-per-line': 'off',
            '@stylistic/member-delimiter-style': [ 'error', { singleline: { delimiter: 'comma' }, multiline: { delimiter: 'comma' } } ],
            '@stylistic/no-multiple-empty-lines': [ 'error', { max: 2 } ],
            '@stylistic/operator-linebreak': 'off',
            '@stylistic/padded-blocks': 'off',
            '@stylistic/quotes': [ 'error', 'single' ],
            '@stylistic/semi': [ 'error', 'always' ],

            // Style (JSX)
            '@stylistic/jsx-indent-props': [ 'error', 4 ],
            '@stylistic/jsx-max-props-per-line': 'off',
            '@stylistic/jsx-one-expression-per-line': 'off',

            // Core rules (possible problems)
            'no-constructor-return': 'error',
            'no-duplicate-imports': 'error',
            'no-self-compare': 'error',
            'no-unmodified-loop-condition': 'error',

            // Core rules (suggestions)
            'camelcase': 'error',
            'curly': 'error',
            'eqeqeq': 'error',
            'no-alert': 'error',
            'no-console': 'error',
            'no-eval': 'error',
            'no-extend-native': 'error',
            'no-implicit-coercion': 'error',
            'no-implied-eval': 'error',
            'no-invalid-this': 'error',
            'no-labels': 'error',
            'no-lone-blocks': 'error',
            'no-new': 'error',
            'no-new-func': 'error',
            'no-new-wrappers': 'error',
            'no-octal-escape': 'error',
            'no-return-assign': [ 'error', 'always' ],
            'no-throw-literal': 'error',
            'no-useless-computed-key': 'error',
            'no-useless-concat': 'error',
            'no-useless-rename': 'error',
            'no-useless-return': 'error',
            'no-var': 'error',
            'no-warning-comments': [ 'warn', { location: 'anywhere', terms: [ 'TODO' ] }],
            'prefer-arrow-callback': 'error',
            'prefer-const': 'error',
            'prefer-regex-literals': 'error',
            'prefer-rest-params': 'error',

            // React rules
            'react/prop-types': 'off',
        },
    },
];
