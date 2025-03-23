import { describe, it, expect, vi, beforeEach } from 'vitest';
import { useAPI } from './useAPI';
import RequestUtility from "@utils/RequestUtility.js";

// Mock dependencies
vi.mock('vue', () => ({
    computed: (fn) => fn(),
}));

vi.mock('vuex', () => ({
    useStore: () => mockStore
}));

vi.mock('@utils/RequestUtility.js', () => ({
    default: {
        sendRequest: vi.fn()
    }
}));

vi.mock('primevue/usetoast', () => ({
    useToast: () => mockToast
}));

// Mock store and toast
const mockStore = {
    commit: vi.fn(),
    state: {
        users: [],
        products: []
    }
};

const mockToast = {
    add: vi.fn()
};

describe('useAPI Composable', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    describe('get method', () => {
        it('should fetch data and commit to store', async () => {
            // Arrange
            const api = useAPI('users');
            const mockResponse = {
                data: {
                    data: [{ id: 1, name: 'Test User' }]
                }
            };

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            const result = api.get();

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'get',
                url: 'users'
            }));
            expect(mockStore.commit).toHaveBeenCalledWith('setUsers', mockResponse.data.data);
            expect(result).toEqual(mockStore.state.users);
        });

        it('should handle errors when fetching data', () => {
            // Arrange
            const api = useAPI('users');
            const consoleErrorSpy = vi.spyOn(console, 'error').mockImplementation(() => {});

            RequestUtility.sendRequest.mockImplementation(({ error }) => {
                error('Error message');
            });

            // Act
            api.get();

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'get',
                url: 'users'
            }));
            expect(consoleErrorSpy).toHaveBeenCalledWith('Failed to get users:', 'Error message');

            consoleErrorSpy.mockRestore();
        });
    });

    describe('post method', () => {
        it('should post data and show success toast', async () => {
            // Arrange
            const api = useAPI('users');
            const formData = { name: 'New User' };
            const mockResponse = { data: { success: true } };

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            const result = await api.post(formData);

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'post',
                data: formData,
                url: 'users'
            }));
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                severity: 'success',
                summary: 'Success'
            }));
            expect(result).toEqual(mockResponse.data);
        });

        it('should handle errors when posting data', async () => {
            // Arrange
            const api = useAPI('users');
            const formData = { name: 'New User' };
            const mockError = {
                response: { data: { data: 'Error message' } }
            };
            const consoleErrorSpy = vi.spyOn(console, 'error').mockImplementation(() => {});

            RequestUtility.sendRequest.mockImplementation(({ error }) => {
                error(mockError);
            });

            // Act & Assert
            await expect(api.post(formData)).rejects.toEqual(mockError);
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                severity: 'error',
                summary: 'Error',
                detail: 'Error message'
            }));

            consoleErrorSpy.mockRestore();
        });
    });

    describe('trash method', () => {
        it('should delete data and show info toast', async () => {
            // Arrange
            const api = useAPI('products');
            const data = { id: 1 };
            const mockResponse = {
                data: {
                    data: {
                        name: 'Test Product'
                    }
                }
            };

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            const result = await api.trash(data);

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'delete',
                data: data,
                url: 'products'
            }));
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                severity: 'info',
                summary: 'Products Deleted',
                detail: 'Test Product has been deleted.'
            }));
            expect(result).toEqual(mockResponse.data);
        });

        it('should handle errors when deleting data', async () => {
            // Arrange
            const api = useAPI('products');
            const data = { id: 1 };
            const mockError = 'Error message';
            const consoleErrorSpy = vi.spyOn(console, 'error').mockImplementation(() => {});

            RequestUtility.sendRequest.mockImplementation(({ error }) => {
                error(mockError);
            });

            // Act & Assert
            await expect(api.trash(data)).rejects.toEqual(mockError);
            expect(consoleErrorSpy).toHaveBeenCalledWith('Failed to delete products:', mockError);

            consoleErrorSpy.mockRestore();
        });
    });

    describe('update method', () => {
        it('should update data via patch and show success toast', async () => {
            // Arrange
            const api = useAPI('products');
            const data = { id: 1, name: 'Updated Product' };
            const mockResponse = { data: { success: true } };

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            const result = await api.update(data);

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'patch',
                data: data,
                url: 'products/'
            }));
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                severity: 'success',
                summary: 'Products Updated'
            }));
            expect(result).toEqual(mockResponse.data);
        });
    });

    describe('status change methods', () => {
        it('should mark item as inactive', async () => {
            // Arrange
            const api = useAPI('products');
            const data = { id: 1 };
            const mockResponse = { data: { success: true } };

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            await api.markInactive(data);

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'patch',
                url: 'products/inactive'
            }));
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                summary: 'Products Archived'
            }));
        });

        it('should mark item as active', async () => {
            // Arrange
            const api = useAPI('products');
            const data = { id: 1 };
            const mockResponse = { data: { success: true } };

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            await api.markActive(data);

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'patch',
                url: 'products/active'
            }));
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                summary: 'Products Unarchived'
            }));
        });

        it('should restore item', async () => {
            // Arrange
            const api = useAPI('products');
            const data = { id: 1 };
            const mockResponse = { data: { success: true } };

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            await api.restore(data);

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'patch',
                url: 'products/restore'
            }));
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                summary: 'Products Restored'
            }));
        });
    });

    describe('print method', () => {
        it('should request a PDF document and open it', async () => {
            // Arrange
            const api = useAPI('reports');
            const data = { id: 1 };
            const mockResponse = {
                data: {
                    data: {
                        pdf: 'base64encodedpdf'
                    }
                }
            };

            // Mock window.open
            const mockWindow = {
                document: {
                    write: vi.fn()
                }
            };

            global.window.open = vi.fn(() => mockWindow);

            RequestUtility.sendRequest.mockImplementation(({ success }) => {
                success(mockResponse);
            });

            // Act
            const result = await api.print(data, 'reports', 'Report');

            // Assert
            expect(RequestUtility.sendRequest).toHaveBeenCalledWith(expect.objectContaining({
                type: 'post',
                data: data,
                url: 'reports'
            }));
            expect(global.window.open).toHaveBeenCalled();
            expect(mockWindow.document.write).toHaveBeenCalledWith(
                `<iframe width='100%' height='100%' src='data:application/pdf;base64,base64encodedpdf'></iframe>`
            );
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                severity: 'success',
                detail: 'Report printed successfully.'
            }));
            expect(result).toEqual(mockResponse.data);
        });

        it('should handle errors when printing document', async () => {
            // Arrange
            const api = useAPI('reports');
            const data = { id: 1 };
            const mockError = 'Error message';
            const consoleErrorSpy = vi.spyOn(console, 'error').mockImplementation(() => {});

            RequestUtility.sendRequest.mockImplementation(({ error }) => {
                error(mockError);
            });

            // Act & Assert
            await expect(api.print(data, 'reports', 'Report')).rejects.toEqual(mockError);
            expect(mockToast.add).toHaveBeenCalledWith(expect.objectContaining({
                severity: 'error',
                detail: 'Failed to print Report.'
            }));

            consoleErrorSpy.mockRestore();
        });
    });
});