namespace Agent
{
    partial class FList
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(FList));
            GWRequests = new DataGridView();
            ColumnId = new DataGridViewTextBoxColumn();
            ColPriority = new DataGridViewComboBoxColumn();
            ColStatus = new DataGridViewComboBoxColumn();
            ColCategory = new DataGridViewComboBoxColumn();
            ColTheme = new DataGridViewTextBoxColumn();
            ColEmployee = new DataGridViewTextBoxColumn();
            ColItSpec = new DataGridViewTextBoxColumn();
            ColItRole = new DataGridViewTextBoxColumn();
            ColPCName = new DataGridViewTextBoxColumn();
            ColCreate = new DataGridViewTextBoxColumn();
            ColDeadline = new DataGridViewTextBoxColumn();
            groupBox1 = new GroupBox();
            CBDeadline = new CheckBox();
            label4 = new Label();
            TBSearch = new TextBox();
            label3 = new Label();
            CBCategory = new ComboBox();
            label2 = new Label();
            CBPriority = new ComboBox();
            label1 = new Label();
            CBStatus = new ComboBox();
            button1 = new Button();
            ((System.ComponentModel.ISupportInitialize)GWRequests).BeginInit();
            groupBox1.SuspendLayout();
            SuspendLayout();
            // 
            // GWRequests
            // 
            GWRequests.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            GWRequests.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            GWRequests.Columns.AddRange(new DataGridViewColumn[] { ColumnId, ColPriority, ColStatus, ColCategory, ColTheme, ColEmployee, ColItSpec, ColItRole, ColPCName, ColCreate, ColDeadline });
            GWRequests.Location = new Point(1, 81);
            GWRequests.Name = "GWRequests";
            GWRequests.RowHeadersWidth = 51;
            GWRequests.Size = new Size(1475, 447);
            GWRequests.TabIndex = 0;
            GWRequests.CellDoubleClick += GWRequests_CellDoubleClick;
            GWRequests.CellPainting += GWRequests_CellPainting;
            // 
            // ColumnId
            // 
            ColumnId.DataPropertyName = "id";
            ColumnId.HeaderText = "Ид";
            ColumnId.MinimumWidth = 6;
            ColumnId.Name = "ColumnId";
            ColumnId.Width = 125;
            // 
            // ColPriority
            // 
            ColPriority.DataPropertyName = "urgency";
            ColPriority.DisplayStyle = DataGridViewComboBoxDisplayStyle.Nothing;
            ColPriority.FlatStyle = FlatStyle.System;
            ColPriority.HeaderText = "Приоритет";
            ColPriority.MinimumWidth = 6;
            ColPriority.Name = "ColPriority";
            ColPriority.Width = 125;
            // 
            // ColStatus
            // 
            ColStatus.DataPropertyName = "status";
            ColStatus.DisplayStyle = DataGridViewComboBoxDisplayStyle.Nothing;
            ColStatus.HeaderText = "Статус";
            ColStatus.MinimumWidth = 6;
            ColStatus.Name = "ColStatus";
            ColStatus.Resizable = DataGridViewTriState.True;
            ColStatus.SortMode = DataGridViewColumnSortMode.Automatic;
            ColStatus.Width = 125;
            // 
            // ColCategory
            // 
            ColCategory.DataPropertyName = "idcategory";
            ColCategory.DisplayStyle = DataGridViewComboBoxDisplayStyle.Nothing;
            ColCategory.HeaderText = "Категория";
            ColCategory.MinimumWidth = 6;
            ColCategory.Name = "ColCategory";
            ColCategory.Width = 125;
            // 
            // ColTheme
            // 
            ColTheme.DataPropertyName = "topic";
            ColTheme.HeaderText = "Тема";
            ColTheme.MinimumWidth = 6;
            ColTheme.Name = "ColTheme";
            ColTheme.Width = 125;
            // 
            // ColEmployee
            // 
            ColEmployee.DataPropertyName = "employeename";
            ColEmployee.HeaderText = "Сотрудник";
            ColEmployee.MinimumWidth = 6;
            ColEmployee.Name = "ColEmployee";
            ColEmployee.Width = 125;
            // 
            // ColItSpec
            // 
            ColItSpec.DataPropertyName = "name_users";
            ColItSpec.HeaderText = "Исполнитель";
            ColItSpec.MinimumWidth = 6;
            ColItSpec.Name = "ColItSpec";
            ColItSpec.Width = 125;
            // 
            // ColItRole
            // 
            ColItRole.DataPropertyName = "itrole_proc_name";
            ColItRole.HeaderText = "ИТ роль";
            ColItRole.MinimumWidth = 6;
            ColItRole.Name = "ColItRole";
            ColItRole.Width = 125;
            // 
            // ColPCName
            // 
            ColPCName.DataPropertyName = "pcname";
            ColPCName.HeaderText = "ИмяПК";
            ColPCName.MinimumWidth = 6;
            ColPCName.Name = "ColPCName";
            ColPCName.Width = 125;
            // 
            // ColCreate
            // 
            ColCreate.DataPropertyName = "dt";
            ColCreate.HeaderText = "Создана";
            ColCreate.MinimumWidth = 6;
            ColCreate.Name = "ColCreate";
            ColCreate.Width = 125;
            // 
            // ColDeadline
            // 
            ColDeadline.DataPropertyName = "deadline";
            ColDeadline.HeaderText = "Срок";
            ColDeadline.MinimumWidth = 6;
            ColDeadline.Name = "ColDeadline";
            ColDeadline.Width = 125;
            // 
            // groupBox1
            // 
            groupBox1.Controls.Add(CBDeadline);
            groupBox1.Controls.Add(label4);
            groupBox1.Controls.Add(TBSearch);
            groupBox1.Controls.Add(label3);
            groupBox1.Controls.Add(CBCategory);
            groupBox1.Controls.Add(label2);
            groupBox1.Controls.Add(CBPriority);
            groupBox1.Controls.Add(label1);
            groupBox1.Controls.Add(CBStatus);
            groupBox1.Location = new Point(6, 12);
            groupBox1.Name = "groupBox1";
            groupBox1.Size = new Size(1336, 63);
            groupBox1.TabIndex = 11;
            groupBox1.TabStop = false;
            groupBox1.Text = "Фильтры";
            // 
            // CBDeadline
            // 
            CBDeadline.AutoSize = true;
            CBDeadline.Location = new Point(1185, 30);
            CBDeadline.Name = "CBDeadline";
            CBDeadline.Size = new Size(138, 24);
            CBDeadline.TabIndex = 19;
            CBDeadline.Text = "Просроченные";
            CBDeadline.UseVisualStyleBackColor = true;
            CBDeadline.Click += CBDeadline_Click;
            // 
            // label4
            // 
            label4.AutoSize = true;
            label4.Location = new Point(898, 30);
            label4.Name = "label4";
            label4.Size = new Size(52, 20);
            label4.TabIndex = 18;
            label4.Text = "Найти";
            // 
            // TBSearch
            // 
            TBSearch.Location = new Point(954, 27);
            TBSearch.Name = "TBSearch";
            TBSearch.Size = new Size(202, 27);
            TBSearch.TabIndex = 17;
            TBSearch.KeyDown += TBSearch_KeyDown;
            // 
            // label3
            // 
            label3.AutoSize = true;
            label3.Location = new Point(587, 29);
            label3.Name = "label3";
            label3.Size = new Size(81, 20);
            label3.TabIndex = 16;
            label3.Text = "Категория";
            // 
            // CBCategory
            // 
            CBCategory.FormattingEnabled = true;
            CBCategory.Location = new Point(674, 26);
            CBCategory.Name = "CBCategory";
            CBCategory.Size = new Size(183, 28);
            CBCategory.TabIndex = 15;
            // 
            // label2
            // 
            label2.AutoSize = true;
            label2.Location = new Point(286, 29);
            label2.Name = "label2";
            label2.Size = new Size(85, 20);
            label2.TabIndex = 14;
            label2.Text = "Приоритет";
            // 
            // CBPriority
            // 
            CBPriority.FormattingEnabled = true;
            CBPriority.Location = new Point(377, 26);
            CBPriority.Name = "CBPriority";
            CBPriority.Size = new Size(181, 28);
            CBPriority.TabIndex = 13;
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.Location = new Point(3, 29);
            label1.Name = "label1";
            label1.Size = new Size(52, 20);
            label1.TabIndex = 12;
            label1.Text = "Статус";
            // 
            // CBStatus
            // 
            CBStatus.FormattingEnabled = true;
            CBStatus.Location = new Point(61, 26);
            CBStatus.Name = "CBStatus";
            CBStatus.Size = new Size(191, 28);
            CBStatus.TabIndex = 11;
            // 
            // button1
            // 
            button1.Anchor = AnchorStyles.Bottom | AnchorStyles.Right;
            button1.Location = new Point(1347, 535);
            button1.Name = "button1";
            button1.Size = new Size(116, 36);
            button1.TabIndex = 12;
            button1.Text = "Закрыть";
            button1.UseVisualStyleBackColor = true;
            button1.Click += button1_Click;
            // 
            // FList
            // 
            AutoScaleDimensions = new SizeF(8F, 20F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(1477, 580);
            Controls.Add(button1);
            Controls.Add(groupBox1);
            Controls.Add(GWRequests);
            Icon = (Icon)resources.GetObject("$this.Icon");
            Name = "FList";
            Text = "Реестр заявок";
            ((System.ComponentModel.ISupportInitialize)GWRequests).EndInit();
            groupBox1.ResumeLayout(false);
            groupBox1.PerformLayout();
            ResumeLayout(false);
        }

        #endregion

        private DataGridView GWRequests;
        private GroupBox groupBox1;
        private CheckBox CBDeadline;
        private Label label4;
        private TextBox TBSearch;
        private Label label3;
        private ComboBox CBCategory;
        private Label label2;
        private ComboBox CBPriority;
        private Label label1;
        private ComboBox CBStatus;
        private Button button1;
        private DataGridViewTextBoxColumn ColumnId;
        private DataGridViewComboBoxColumn ColPriority;
        private DataGridViewComboBoxColumn ColStatus;
        private DataGridViewComboBoxColumn ColCategory;
        private DataGridViewTextBoxColumn ColTheme;
        private DataGridViewTextBoxColumn ColEmployee;
        private DataGridViewTextBoxColumn ColItSpec;
        private DataGridViewTextBoxColumn ColItRole;
        private DataGridViewTextBoxColumn ColPCName;
        private DataGridViewTextBoxColumn ColCreate;
        private DataGridViewTextBoxColumn ColDeadline;
    }
}