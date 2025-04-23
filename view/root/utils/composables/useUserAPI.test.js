import {beforeEach, describe, expect, it, vi} from 'vitest'
import {useUserAPI} from './useUserAPI.js'

// Mock Vuex store
vi.mock('vuex', () => ({
    useStore: vi.fn(() => ({
        commit: vi.fn(),
        state: {
            user: {id: 1, name: 'Test User'}
        }
    }))
}))

// Mock Vue Router
vi.mock('vue-router', () => ({
    useRouter: vi.fn(() => ({
        push: vi.fn()
    }))
}))

// Mock RequestUtility
let mockRequestResponse
vi.mock('@utils/RequestUtility.js', () => ({
    default: {
        sendRequest: vi.fn(() => mockRequestResponse)
    }
}))

describe('useUserAPI', () => {
    let userAPI
    let store
    let router
    let RequestUtility

    beforeEach(async () => {
        // Import the mocked modules
        const vuex = await import('vuex')
        const vueRouter = await import('vue-router')
        const requestUtilityModule = await import('@utils/RequestUtility.js')

        store = vuex.useStore()
        router = vueRouter.useRouter()
        RequestUtility = requestUtilityModule.default

        userAPI = useUserAPI()
    })

    describe('getCurrentUser', () => {
        it('should fetch current user using requestUtility', () => {
            // Call the getCurrentUser function
            userAPI.getCurrentUser()

            // Verify RequestUtility.sendRequest was called with correct request config
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith({
                type: 'get',
                url: 'users/current',
                success: expect.any(Function),
                error: expect.any(Function)
            })
        })


        it('should return computed user state', () => {
            const user = userAPI.getCurrentUser()
            expect(user.value).toEqual({id: 1, name: 'Test User'})
        })
    })

    describe('logOut', () => {
        it('should specify request data to log out', () => {
            // Call the getCurrentUser function
            userAPI.logOut()

            // Verify RequestUtility.sendRequest was called with correct request config
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith({
                type: 'post',
                url: 'users/logout',
                success: expect.any(Function),
                error: expect.any(Function)
            })
        })
    })
}) 