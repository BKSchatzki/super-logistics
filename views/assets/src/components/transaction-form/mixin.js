export default {
    data () {
        return {

        }
    },
    methods: {
        addTransaction(transaction) {
            // Adds a new Transaction to the database
            const self = this;
            const request_data = {
                type: 'POST',
                url: self.base_url + 'sl/v1/transactions/',
                data: transaction,
                success: function (res) {
                    console.log('Transaction added:', res);
                },
                error: function (res) {
                    console.error('Failed to set project as boardable:', res);
                }
            };
            self.httpRequest(request_data);
        },
        updateProjectBoardable(from_board_id, project_id, to_board_id) {
            const self = this;
            const request_data = {
                type: 'PUT',
                url: self.base_url + 'pm/v2/global-kanboard/boardable',
                data: {from_board_id, project_id, to_board_id},
                error: function (res) {
                    console.error('Failed to update project as boardable:', res);
                }
            };
            self.httpRequest(request_data);
        },
        removeProjectBoardable(board_id, project_id) {
            // Removes Project from pm_boardables table in the database
            // Not to be confused for use as addProjectBoardable or updateProjectBoardable
            const self = this;
            const request_data = {
                type: 'DELETE',
                url: self.base_url + 'pm/v2/global-kanboard/' + board_id + '/boardable/' + project_id,
                success: function (res) {
                    self.getProjectBoardables(board_id);
                },
                error: function (res) {
                    console.error('Failed to remove project as boardable:', res);
                }
            };
            self.httpRequest(request_data);
        },
        addBoard(title) {
          const self = this;
          const request_data = {
            type: 'POST',
            url: self.base_url + 'pm/v2/global-kanboard',
            data: { title },
            success: function (res) {
              self.getGlobalKanban();
            },
            error: function (res) {
              console.error('Failed to add board:', res);
            }
          };
          self.httpRequest(request_data);
        },
        deleteBoard(board_id) {
          const self = this;
          const request_data = {
            type: 'DELETE',
            url: self.base_url + 'pm/v2/global-kanboard/' + board_id,
            success: function (res) {
              console.log("board deleted");
              self.getGlobalKanban();
            },
            error: function (res) {
              console.error('Failed to delete board:', res);
            }
          };
          self.httpRequest(request_data);
        },
        updateBoardOrder(updated_boards) {
          const self = this;

          for (let i = 0; i < updated_boards.length; i++) {
            updated_boards[i].order = i;
          }

          const request_data = {
            type: 'PUT',
            url: self.base_url + 'pm/v2/global-kanboard/',
            data: { updated_boards },
            error: function (res) {
              console.error('Failed to update board order:', res);
            }
          };
          self.httpRequest(request_data);
        }
    }
}
