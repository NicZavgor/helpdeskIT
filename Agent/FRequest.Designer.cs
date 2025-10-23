namespace Agent
{
    partial class FRequest
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
            components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(FRequest));
            GB1 = new GroupBox();
            CBStatus = new ComboBox();
            LStatus = new Label();
            label4 = new Label();
            UserDisplayName = new Label();
            label2 = new Label();
            UserDomainName = new Label();
            label3 = new Label();
            PCName = new Label();
            label1 = new Label();
            GB3 = new GroupBox();
            TBMessage = new TextBox();
            BOk = new Button();
            BCancel = new Button();
            bindingSource1 = new BindingSource(components);
            GBRequestSolving = new GroupBox();
            flpChat = new FlowLayoutPanel();
            TBChatMsg = new TextBox();
            BChatSendMsg = new Button();
            GB2 = new GroupBox();
            LTopic = new Label();
            TBTopic = new TextBox();
            label7 = new Label();
            TPDeadline = new DateTimePicker();
            label6 = new Label();
            CBCategories = new ComboBox();
            label5 = new Label();
            CBItSpec = new ComboBox();
            RBItSpec = new RadioButton();
            CBUrgency = new ComboBox();
            RBProgrammist = new RadioButton();
            RBAdmin = new RadioButton();
            RBBoss = new RadioButton();
            GBAnswer = new GroupBox();
            GWRequestProc = new DataGridView();
            ColDeadline = new DataGridViewTextBoxColumn();
            ColPCName = new DataGridViewTextBoxColumn();
            ColItSpec = new DataGridViewTextBoxColumn();
            ColCreate = new DataGridViewTextBoxColumn();
            BClose = new Button();
            GB1.SuspendLayout();
            GB3.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)bindingSource1).BeginInit();
            GBRequestSolving.SuspendLayout();
            GB2.SuspendLayout();
            GBAnswer.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)GWRequestProc).BeginInit();
            SuspendLayout();
            // 
            // GB1
            // 
            GB1.Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right;
            GB1.Controls.Add(CBStatus);
            GB1.Controls.Add(LStatus);
            GB1.Controls.Add(label4);
            GB1.Controls.Add(UserDisplayName);
            GB1.Controls.Add(label2);
            GB1.Controls.Add(UserDomainName);
            GB1.Controls.Add(label3);
            GB1.Controls.Add(PCName);
            GB1.Controls.Add(label1);
            GB1.Location = new Point(12, 2);
            GB1.Name = "GB1";
            GB1.Size = new Size(1055, 53);
            GB1.TabIndex = 0;
            GB1.TabStop = false;
            // 
            // CBStatus
            // 
            CBStatus.Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right;
            CBStatus.Enabled = false;
            CBStatus.FormattingEnabled = true;
            CBStatus.Items.AddRange(new object[] { "Пожелание", "Текущий ворпос", "Решить до вечера", "Срочно" });
            CBStatus.Location = new Point(821, 17);
            CBStatus.Name = "CBStatus";
            CBStatus.Size = new Size(218, 28);
            CBStatus.TabIndex = 0;
            // 
            // LStatus
            // 
            LStatus.AutoSize = true;
            LStatus.Location = new Point(792, 23);
            LStatus.Name = "LStatus";
            LStatus.Size = new Size(0, 20);
            LStatus.TabIndex = 7;
            // 
            // label4
            // 
            label4.AutoSize = true;
            label4.Location = new Point(753, 20);
            label4.Name = "label4";
            label4.Size = new Size(52, 20);
            label4.TabIndex = 6;
            label4.Text = "Статус";
            // 
            // UserDisplayName
            // 
            UserDisplayName.AutoSize = true;
            UserDisplayName.Location = new Point(540, 18);
            UserDisplayName.Name = "UserDisplayName";
            UserDisplayName.Size = new Size(0, 20);
            UserDisplayName.TabIndex = 5;
            // 
            // label2
            // 
            label2.AutoSize = true;
            label2.Location = new Point(495, 18);
            label2.Name = "label2";
            label2.Size = new Size(39, 20);
            label2.TabIndex = 4;
            label2.Text = "Имя";
            // 
            // UserDomainName
            // 
            UserDomainName.AutoSize = true;
            UserDomainName.Location = new Point(304, 18);
            UserDomainName.Name = "UserDomainName";
            UserDomainName.Size = new Size(0, 20);
            UserDomainName.TabIndex = 3;
            // 
            // label3
            // 
            label3.AutoSize = true;
            label3.Location = new Point(232, 18);
            label3.Name = "label3";
            label3.Size = new Size(55, 20);
            label3.TabIndex = 2;
            label3.Text = "Учетка";
            // 
            // PCName
            // 
            PCName.AutoSize = true;
            PCName.Location = new Point(77, 18);
            PCName.Name = "PCName";
            PCName.Size = new Size(0, 20);
            PCName.TabIndex = 1;
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.Location = new Point(8, 18);
            label1.Name = "label1";
            label1.Size = new Size(63, 20);
            label1.TabIndex = 0;
            label1.Text = "Имя ПК";
            // 
            // GB3
            // 
            GB3.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            GB3.Controls.Add(TBMessage);
            GB3.Location = new Point(12, 214);
            GB3.Name = "GB3";
            GB3.Size = new Size(567, 199);
            GB3.TabIndex = 2;
            GB3.TabStop = false;
            GB3.Text = "Текст заявки";
            // 
            // TBMessage
            // 
            TBMessage.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            TBMessage.Location = new Point(6, 25);
            TBMessage.Multiline = true;
            TBMessage.Name = "TBMessage";
            TBMessage.Size = new Size(557, 168);
            TBMessage.TabIndex = 12;
            // 
            // BOk
            // 
            BOk.Anchor = AnchorStyles.Bottom | AnchorStyles.Left;
            BOk.DialogResult = DialogResult.OK;
            BOk.Location = new Point(12, 679);
            BOk.Name = "BOk";
            BOk.Size = new Size(138, 41);
            BOk.TabIndex = 5;
            BOk.Text = "Записать";
            BOk.UseVisualStyleBackColor = true;
            // 
            // BCancel
            // 
            BCancel.Anchor = AnchorStyles.Bottom | AnchorStyles.Left;
            BCancel.Location = new Point(161, 679);
            BCancel.Name = "BCancel";
            BCancel.Size = new Size(138, 41);
            BCancel.TabIndex = 6;
            BCancel.Text = "Отмена";
            BCancel.UseVisualStyleBackColor = true;
            BCancel.Click += BCancel_Click;
            // 
            // GBRequestSolving
            // 
            GBRequestSolving.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Right;
            GBRequestSolving.Controls.Add(flpChat);
            GBRequestSolving.Controls.Add(TBChatMsg);
            GBRequestSolving.Controls.Add(BChatSendMsg);
            GBRequestSolving.Location = new Point(585, 214);
            GBRequestSolving.Name = "GBRequestSolving";
            GBRequestSolving.Size = new Size(482, 456);
            GBRequestSolving.TabIndex = 3;
            GBRequestSolving.TabStop = false;
            GBRequestSolving.Text = "Чат";
            // 
            // flpChat
            // 
            flpChat.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            flpChat.AutoScroll = true;
            flpChat.BackColor = Color.White;
            flpChat.FlowDirection = FlowDirection.TopDown;
            flpChat.Location = new Point(6, 23);
            flpChat.Name = "flpChat";
            flpChat.Padding = new Padding(10);
            flpChat.Size = new Size(470, 331);
            flpChat.TabIndex = 13;
            flpChat.WrapContents = false;
            // 
            // TBChatMsg
            // 
            TBChatMsg.Anchor = AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            TBChatMsg.Location = new Point(6, 359);
            TBChatMsg.Multiline = true;
            TBChatMsg.Name = "TBChatMsg";
            TBChatMsg.Size = new Size(362, 91);
            TBChatMsg.TabIndex = 14;
            TBChatMsg.KeyDown += TBChatMsg_KeyDown;
            // 
            // BChatSendMsg
            // 
            BChatSendMsg.Anchor = AnchorStyles.Bottom | AnchorStyles.Right;
            BChatSendMsg.Location = new Point(374, 360);
            BChatSendMsg.Name = "BChatSendMsg";
            BChatSendMsg.Size = new Size(102, 90);
            BChatSendMsg.TabIndex = 15;
            BChatSendMsg.Text = "Отправить";
            BChatSendMsg.UseVisualStyleBackColor = true;
            BChatSendMsg.Click += BChatSendMsg_Click;
            // 
            // GB2
            // 
            GB2.Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right;
            GB2.Controls.Add(LTopic);
            GB2.Controls.Add(TBTopic);
            GB2.Controls.Add(label7);
            GB2.Controls.Add(TPDeadline);
            GB2.Controls.Add(label6);
            GB2.Controls.Add(CBCategories);
            GB2.Controls.Add(label5);
            GB2.Controls.Add(CBItSpec);
            GB2.Controls.Add(RBItSpec);
            GB2.Controls.Add(CBUrgency);
            GB2.Controls.Add(RBProgrammist);
            GB2.Controls.Add(RBAdmin);
            GB2.Controls.Add(RBBoss);
            GB2.Location = new Point(13, 61);
            GB2.Name = "GB2";
            GB2.Size = new Size(1054, 147);
            GB2.TabIndex = 1;
            GB2.TabStop = false;
            // 
            // LTopic
            // 
            LTopic.AutoSize = true;
            LTopic.Location = new Point(17, 106);
            LTopic.Name = "LTopic";
            LTopic.Size = new Size(44, 20);
            LTopic.TabIndex = 16;
            LTopic.Text = "Тема";
            // 
            // TBTopic
            // 
            TBTopic.Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right;
            TBTopic.Location = new Point(76, 103);
            TBTopic.Name = "TBTopic";
            TBTopic.Size = new Size(960, 27);
            TBTopic.TabIndex = 11;
            // 
            // label7
            // 
            label7.AutoSize = true;
            label7.Location = new Point(771, 25);
            label7.Name = "label7";
            label7.Size = new Size(43, 20);
            label7.TabIndex = 14;
            label7.Text = "Срок";
            // 
            // TPDeadline
            // 
            TPDeadline.Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right;
            TPDeadline.Location = new Point(820, 21);
            TPDeadline.Name = "TPDeadline";
            TPDeadline.Size = new Size(216, 27);
            TPDeadline.TabIndex = 5;
            // 
            // label6
            // 
            label6.AutoSize = true;
            label6.Location = new Point(403, 26);
            label6.Name = "label6";
            label6.Size = new Size(81, 20);
            label6.TabIndex = 12;
            label6.Text = "Категория";
            // 
            // CBCategories
            // 
            CBCategories.FormattingEnabled = true;
            CBCategories.Items.AddRange(new object[] { "Пожелание", "Текущий ворпос", "Решить до вечера", "Срочно" });
            CBCategories.Location = new Point(490, 23);
            CBCategories.Name = "CBCategories";
            CBCategories.Size = new Size(275, 28);
            CBCategories.TabIndex = 4;
            // 
            // label5
            // 
            label5.AutoSize = true;
            label5.Location = new Point(17, 26);
            label5.Name = "label5";
            label5.Size = new Size(85, 20);
            label5.TabIndex = 10;
            label5.Text = "Приоритет";
            // 
            // CBItSpec
            // 
            CBItSpec.Anchor = AnchorStyles.Top | AnchorStyles.Left | AnchorStyles.Right;
            CBItSpec.FormattingEnabled = true;
            CBItSpec.Items.AddRange(new object[] { "Пожелание", "Текущий ворпос", "Решить до вечера", "Срочно" });
            CBItSpec.Location = new Point(820, 61);
            CBItSpec.Name = "CBItSpec";
            CBItSpec.Size = new Size(216, 28);
            CBItSpec.TabIndex = 10;
            // 
            // RBItSpec
            // 
            RBItSpec.AutoSize = true;
            RBItSpec.Location = new Point(655, 61);
            RBItSpec.Name = "RBItSpec";
            RBItSpec.Size = new Size(119, 24);
            RBItSpec.TabIndex = 9;
            RBItSpec.TabStop = true;
            RBItSpec.Text = "Специалисту";
            RBItSpec.UseVisualStyleBackColor = true;
            RBItSpec.Click += RBItSpec_Click;
            // 
            // CBUrgency
            // 
            CBUrgency.FormattingEnabled = true;
            CBUrgency.Items.AddRange(new object[] { "Пожелание", "Текущий ворпос", "Решить до вечера", "Срочно" });
            CBUrgency.Location = new Point(108, 22);
            CBUrgency.Name = "CBUrgency";
            CBUrgency.Size = new Size(283, 28);
            CBUrgency.TabIndex = 3;
            // 
            // RBProgrammist
            // 
            RBProgrammist.AutoSize = true;
            RBProgrammist.Location = new Point(468, 60);
            RBProgrammist.Name = "RBProgrammist";
            RBProgrammist.Size = new Size(133, 24);
            RBProgrammist.TabIndex = 8;
            RBProgrammist.TabStop = true;
            RBProgrammist.Text = "Программисту";
            RBProgrammist.UseVisualStyleBackColor = true;
            RBProgrammist.Click += RBBoss_Click;
            // 
            // RBAdmin
            // 
            RBAdmin.AutoSize = true;
            RBAdmin.Location = new Point(198, 61);
            RBAdmin.Name = "RBAdmin";
            RBAdmin.Size = new Size(235, 24);
            RBAdmin.TabIndex = 7;
            RBAdmin.TabStop = true;
            RBAdmin.Text = "Системному администратору";
            RBAdmin.UseVisualStyleBackColor = true;
            RBAdmin.Click += RBBoss_Click;
            // 
            // RBBoss
            // 
            RBBoss.AutoSize = true;
            RBBoss.Location = new Point(17, 60);
            RBBoss.Name = "RBBoss";
            RBBoss.Size = new Size(129, 24);
            RBBoss.TabIndex = 6;
            RBBoss.TabStop = true;
            RBBoss.Text = "Руководителю";
            RBBoss.UseVisualStyleBackColor = true;
            RBBoss.Click += RBBoss_Click;
            // 
            // GBAnswer
            // 
            GBAnswer.Anchor = AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            GBAnswer.Controls.Add(GWRequestProc);
            GBAnswer.Location = new Point(13, 419);
            GBAnswer.Name = "GBAnswer";
            GBAnswer.Size = new Size(562, 251);
            GBAnswer.TabIndex = 4;
            GBAnswer.TabStop = false;
            GBAnswer.Text = "Работы по заявке";
            // 
            // GWRequestProc
            // 
            GWRequestProc.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            GWRequestProc.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            GWRequestProc.Columns.AddRange(new DataGridViewColumn[] { ColDeadline, ColPCName, ColItSpec, ColCreate });
            GWRequestProc.Location = new Point(7, 17);
            GWRequestProc.Name = "GWRequestProc";
            GWRequestProc.RowHeadersWidth = 51;
            GWRequestProc.Size = new Size(549, 228);
            GWRequestProc.TabIndex = 11;
            // 
            // ColDeadline
            // 
            ColDeadline.DataPropertyName = "dt";
            ColDeadline.HeaderText = "Событие";
            ColDeadline.MinimumWidth = 6;
            ColDeadline.Name = "ColDeadline";
            ColDeadline.Width = 125;
            // 
            // ColPCName
            // 
            ColPCName.DataPropertyName = "description";
            ColPCName.HeaderText = "Описание";
            ColPCName.MinimumWidth = 6;
            ColPCName.Name = "ColPCName";
            ColPCName.Width = 125;
            // 
            // ColItSpec
            // 
            ColItSpec.DataPropertyName = "status_name";
            ColItSpec.HeaderText = "Статус";
            ColItSpec.MinimumWidth = 6;
            ColItSpec.Name = "ColItSpec";
            ColItSpec.Width = 125;
            // 
            // ColCreate
            // 
            ColCreate.DataPropertyName = "user_name";
            ColCreate.HeaderText = "Сотрудник";
            ColCreate.MinimumWidth = 6;
            ColCreate.Name = "ColCreate";
            ColCreate.Width = 125;
            // 
            // BClose
            // 
            BClose.Anchor = AnchorStyles.Bottom | AnchorStyles.Left;
            BClose.DialogResult = DialogResult.OK;
            BClose.Location = new Point(929, 679);
            BClose.Name = "BClose";
            BClose.Size = new Size(138, 41);
            BClose.TabIndex = 7;
            BClose.Text = "Закрыть";
            BClose.UseVisualStyleBackColor = true;
            BClose.Click += bClose_Click;
            // 
            // FRequest
            // 
            AcceptButton = BOk;
            AutoScaleDimensions = new SizeF(8F, 20F);
            AutoScaleMode = AutoScaleMode.Font;
            CancelButton = BCancel;
            ClientSize = new Size(1077, 732);
            Controls.Add(BClose);
            Controls.Add(GBAnswer);
            Controls.Add(GB2);
            Controls.Add(GBRequestSolving);
            Controls.Add(BCancel);
            Controls.Add(BOk);
            Controls.Add(GB3);
            Controls.Add(GB1);
            Icon = (Icon)resources.GetObject("$this.Icon");
            Name = "FRequest";
            Text = "Заявка";
            FormClosing += FRequest_FormClosing;
            GB1.ResumeLayout(false);
            GB1.PerformLayout();
            GB3.ResumeLayout(false);
            GB3.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)bindingSource1).EndInit();
            GBRequestSolving.ResumeLayout(false);
            GBRequestSolving.PerformLayout();
            GB2.ResumeLayout(false);
            GB2.PerformLayout();
            GBAnswer.ResumeLayout(false);
            ((System.ComponentModel.ISupportInitialize)GWRequestProc).EndInit();
            ResumeLayout(false);
        }

        #endregion

        private GroupBox GB1;
        public Label UserDomainName;
        private Label label3;
        public Label PCName;
        private Label label1;
        private GroupBox GB3;
        private TextBox TBMessage;
        public Button BOk;
        private Button BCancel;
        private BindingSource bindingSource1;
        private Label UserDisplayName;
        private Label label2;
        private GroupBox GBRequestSolving;
        private GroupBox GB2;
        private ComboBox CBUrgency;
        private RadioButton RBProgrammist;
        private RadioButton RBAdmin;
        private RadioButton RBBoss;
        private Label label4;
        private Label LStatus;
        private Label label5;
        private ComboBox CBItSpec;
        private RadioButton RBItSpec;
        private Label label6;
        private ComboBox CBCategories;
        private Label label7;
        private DateTimePicker TPDeadline;
        public Button BChatSendMsg;
        private GroupBox GBAnswer;
        private TextBox ЕМ;
        private Label LTopic;
        private TextBox TBTopic;
        private ComboBox CBStatus;
        private TextBox TBChatMsg;
        private FlowLayoutPanel flpChat;
        private DataGridView GWRequestProc;
        private DataGridViewTextBoxColumn ColDeadline;
        private DataGridViewTextBoxColumn ColPCName;
        private DataGridViewTextBoxColumn ColItSpec;
        private DataGridViewTextBoxColumn ColCreate;
        public Button BClose;
    }
}