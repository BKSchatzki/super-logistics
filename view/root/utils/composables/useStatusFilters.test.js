import { computed } from 'vue'
import { describe, it, expect, vi, beforeEach } from 'vitest'
import { useStatusFilters } from './useStatusFilters.js'

// Mock useAPI
const mockGet = vi.fn().mockReturnValue(computed(() => []))
vi.mock('@utils/composables/useAPI.js', () => ({
    useAPI: vi.fn(() => ({
        get: mockGet
    }))
}))

describe('useStatusFilters', () => {
    let statusFilters

    beforeEach(async () => {
        statusFilters = useStatusFilters('test-topic')
    })

    describe('initialization', () => {
        it('should initialize with default values', () => {
            expect(statusFilters.statusBoxes.active).toBe(true)
            expect(statusFilters.statusBoxes.inactive).toBe(false)
            expect(statusFilters.statusBoxes.deleted).toBe(false)
            expect(statusFilters.statusBoxes.none).toBe(false)
        })

        it('should make initial API call with default params', () => {
            expect(mockGet).toHaveBeenCalledWith({ active: 1, trashed: 0 })
        })
    })

    describe('statusParams', () => {
        describe('active parameter', () => {
            it('should return 1 when only active box is checked', () => {
                statusFilters.statusBoxes.active = true
                statusFilters.statusBoxes.inactive = false
                
                expect(statusFilters.statusParams.active).toBe(1)
            })

            it('should return 0 when only inactive box is checked', () => {
                statusFilters.statusBoxes.active = false
                statusFilters.statusBoxes.inactive = true
                
                expect(statusFilters.statusParams.active).toBe(0)
            })

            it('should return null when both active and inactive boxes are checked', () => {
                statusFilters.statusBoxes.active = true
                statusFilters.statusBoxes.inactive = true
                
                expect(statusFilters.statusParams.active).toBe(null)
            })
        })

        describe('trashed parameter', () => {
            it('should return 1 when only deleted box is checked', () => {
                statusFilters.statusBoxes.active = false
                statusFilters.statusBoxes.inactive = false
                statusFilters.statusBoxes.deleted = true
                
                expect(statusFilters.statusParams.trashed).toBe(1)
            })

            it('should return 0 when active/inactive checked and deleted unchecked', () => {
                statusFilters.statusBoxes.active = true
                statusFilters.statusBoxes.inactive = false
                statusFilters.statusBoxes.deleted = false
                
                expect(statusFilters.statusParams.trashed).toBe(0)
            })

            it('should return null when deleted is checked with active/inactive', () => {
                statusFilters.statusBoxes.active = true
                statusFilters.statusBoxes.inactive = false
                statusFilters.statusBoxes.deleted = true
                
                expect(statusFilters.statusParams.trashed).toBe(null)
            })
        })
    })

    describe('statusStyles', () => {
        it('should return deleted style for trashed items', () => {
            const style = statusFilters.statusStyles({ trashed: 1 })
            expect(style).toEqual({
                'border-radius': '10px',
                'background': 'rgba(255, 0, 0, 0.2)'
            })
        })

        it('should return inactive style for inactive items', () => {
            const style = statusFilters.statusStyles({ trashed: 0, active: 0 })
            expect(style).toEqual({
                'border-radius': '10px',
                'background': 'rgba(150, 150, 150, 0.3)'
            })
        })

        it('should return default style for active items', () => {
            // Testing the style of the "transaction", argument is not for query
            const style = statusFilters.statusStyles({ trashed: false, active: true })
            expect(style).toEqual({
                'border-radius': '10px'
            })
        })
    })

    describe('watchEffect', () => {
        it('should update data when status params change', () => {
            // Trigger status param changes
            statusFilters.statusBoxes.active = false
            statusFilters.statusBoxes.inactive = true
            
            expect(mockGet).toHaveBeenCalledWith(expect.objectContaining({
                active: 0,
                trashed: 0
            }))
        })
    })
}) 