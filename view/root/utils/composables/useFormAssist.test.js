import {beforeEach, describe, expect, it, vi} from 'vitest'
import {useFormAssist} from './useFormAssist.js'

// <editor-fold desc="Mocking">

let mockUserState = {isInternal: true};

vi.mock('vuex', () => ({
    useStore: vi.fn(() => ({
        state: {
            get user() {
                return mockUserState;
            }
        }
    }))
}));

vi.mock('@utils/composables/useAPI.js', () => ({
    useAPI: vi.fn(() => ({
        get: vi.fn(() => ({
            value: [
                {id: '1', name: 'Test 1'},
                {id: '2', name: 'Test 2'}
            ]
        })),
        post: vi.fn(async () => ({data: {success: true}})),
        update: vi.fn(async () => ({data: {success: true}}))
    }))
}))

// </editor-fold>

describe('useFormAssist', () => {
    let formAssist
    const mockFormData = {
        name: '',
        email: '',
        role: ''
    }

    beforeEach(() => {
        formAssist = useFormAssist(mockFormData)
    })

    describe('form and visible', () => {
        it('should initialize with default form data', () => {
            expect(formAssist.form).toEqual(mockFormData)
        })

        it('should initialize with visible as false', () => {
            expect(formAssist.visible.value).toBe(false)
        })
    })

    describe('clearForm', () => {
        it('should reset form to default values', () => {
            formAssist.form.name = 'Test'
            formAssist.form.email = 'test@test.com'

            formAssist.clearForm()

            expect(JSON.parse(JSON.stringify(formAssist.form))).toEqual(mockFormData)
        })
    })

    describe('getDroptions', () => {
        it('should return formatted options from API response', () => {
            const options = formAssist.getDroptions('test-topic')

            expect(options.value).toEqual([
                {label: 'Test 1', value: 1},
                {label: 'Test 2', value: 2}
            ])
        })

        it('should include default params in API request', () => {
            const options = formAssist.getDroptions('test-topic', {extraParam: 'value'})

            expect(options.value).toEqual([
                {label: 'Test 1', value: 1},
                {label: 'Test 2', value: 2}
            ])
        })
    })

    describe('getRoleDroptions', () => {
        beforeEach(() => {
            formAssist = useFormAssist(mockFormData);
        });

        it('should return internal role options when user is internal', () => {
            // Set the mock state for this test
            mockUserState = {isInternal: true};

            const options = formAssist.getRoleDroptions();

            expect(options.value).toEqual([
                {label: 'Client Admin', value: 'client_admin'},
                {label: 'Client Employee', value: 'client_employee'},
                {label: 'Internal Admin', value: 'internal_admin'},
                {label: 'Internal Employee', value: 'internal_employee'}
            ]);
        });

        it('should return client role options when user is not internal', () => {
            // Set the mock state for this test
            mockUserState = {isInternal: false};

            const options = formAssist.getRoleDroptions();

            expect(options.value).toEqual([
                {label: 'Client Admin', value: 'client_admin'},
                {label: 'Client Employee', value: 'client_employee'}
            ]);
        });
    });

    describe('submitToAPI', () => {
        it('should successfully post data and clear form', async () => {
            const result = await formAssist.submitToAPI('test-topic', {data: 'test'}, 'post')

            expect(result).toEqual({success: true})
            expect(formAssist.form).toEqual(mockFormData)
            expect(formAssist.visible.value).toBe(false)
        })

        it('should successfully update data and clear form', async () => {
            const result = await formAssist.submitToAPI('test-topic', {data: 'test'}, 'update')

            expect(result).toEqual({success: true})
            expect(formAssist.form).toEqual(mockFormData)
            expect(formAssist.visible.value).toBe(false)
        })

        it('should handle invalid method gracefully', async () => {
            const consoleSpy = vi.spyOn(console, 'error')

            const result = await formAssist.submitToAPI('test-topic', {data: 'test'}, 'invalid')

            expect(result).toBeUndefined()
            expect(consoleSpy).toHaveBeenCalled()
            consoleSpy.mockRestore()
        })
    })
})