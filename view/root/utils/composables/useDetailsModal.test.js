import { describe, it, expect, vi, beforeEach } from 'vitest'
import { useDetailsModal } from './useDetailsModal.js'

// Mock Vue's ref
vi.mock('vue', () => ({
    ref: vi.fn((val) => ({
        value: val
    }))
}))

describe('useDetailsModal', () => {
    let detailsModal

    beforeEach(() => {
        detailsModal = useDetailsModal()
    })

    describe('initialization', () => {
        it('should initialize with selected as null', () => {
            expect(detailsModal.selected.value).toBe(null)
        })
    })

    describe('unselect', () => {
        it('should set selected to null', () => {
            // Set initial value
            detailsModal.selected.value = { id: 1, name: 'Test' }
            
            detailsModal.unselect()
            
            expect(detailsModal.selected.value).toBe(null)
        })
    })

    describe('openDetails', () => {
        it('should set selected to event data', () => {
            const mockEvent = {
                data: { id: 1, name: 'Test Item' }
            }
            
            detailsModal.openDetails(mockEvent)
            
            expect(detailsModal.selected.value).toEqual(mockEvent.data)
        })

        it('should override previous selection', () => {
            // Set initial selection
            detailsModal.selected.value = { id: 1, name: 'First Item' }
            
            const mockEvent = {
                data: { id: 2, name: 'Second Item' }
            }
            
            detailsModal.openDetails(mockEvent)
            
            expect(detailsModal.selected.value).toEqual(mockEvent.data)
        })
    })
}) 