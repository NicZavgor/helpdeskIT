using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System;
using static System.Windows.Forms.LinkLabel;
using System.Management;
using static Agent.PCInfo;
using Microsoft.AspNetCore.Authentication;
using System.DirectoryServices.AccountManagement;

namespace Agent
{
    public class PCInfo
    {
        public class ElementInfo {
            public string TypeName = "";
            public string ModelName = "";
            public int cnt = 0;            
            public string FullDescription = "";            
            public bool isSystemInfo = false;
        }
        public List<ElementInfo> LInfo = null;
        
        public PCInfo() { 
            LInfo = new List<ElementInfo>();
        }

        public void CollectInfo()
        {
            LInfo = GetSystemInfo(LInfo); 
            Application.DoEvents();
            LInfo = GetCpuInfo(LInfo); 
            Application.DoEvents();
            LInfo = GetMemoryInfo(LInfo); 
            Application.DoEvents();
            LInfo = GetDiskInfo(LInfo); 
            Application.DoEvents();
            LInfo = GetGpuInfo(LInfo);
            Application.DoEvents();
            LInfo = GetNetworkInfo(LInfo);
            Application.DoEvents();
            LInfo = GetOsInfo(LInfo ); 
            Application.DoEvents();
        }

        static List<ElementInfo> GetSystemInfo(List<ElementInfo> Lst)
        {
            //Имя ПК
            ElementInfo elementInfo = new ElementInfo();            
            elementInfo.TypeName = "Имя ПК"; elementInfo.ModelName = Environment.MachineName; Lst.Add(elementInfo);

            //Имя доменного пользователя
            elementInfo = new ElementInfo();            
            elementInfo.TypeName = "Пользователь"; elementInfo.ModelName = Environment.UserName; Lst.Add(elementInfo);

            //Полное имя
            if (!string.Equals(Environment.GetEnvironmentVariable("USERDOMAIN"), Environment.GetEnvironmentVariable("COMPUTERNAME"), StringComparison.OrdinalIgnoreCase)) { 
                using (PrincipalContext context = new PrincipalContext(ContextType.Domain))
                {
                    UserPrincipal user = UserPrincipal.FindByIdentity(context, IdentityType.SamAccountName, Environment.UserName);
                    if (user != null)
                    {
                        elementInfo = new ElementInfo();
                        elementInfo.TypeName = "Полное имя"; elementInfo.ModelName = user.DisplayName; Lst.Add(elementInfo);
                    }
                }
            }
            elementInfo = new ElementInfo();
            elementInfo.TypeName = "Количество процессоров"; elementInfo.cnt = Environment.ProcessorCount; Lst.Add(elementInfo);
            return Lst;
            
        }

        static List<ElementInfo> GetCpuInfo(List<ElementInfo> Lst)
        {
            ElementInfo elementInfo = null;
            try
            {
                ManagementObjectSearcher searcher = new ManagementObjectSearcher("root\\CIMV2", "SELECT * FROM Win32_Processor");
                elementInfo = new ElementInfo();
                elementInfo.isSystemInfo = true;
                foreach (ManagementObject obj in searcher.Get())
                {
                    elementInfo.TypeName = "Процессор";
                    elementInfo.ModelName = obj["Name"] + " " + Math.Round(Convert.ToDouble(obj["MaxClockSpeed"]) / 1000, 2) + " GHz";
                    elementInfo.FullDescription = elementInfo.FullDescription + obj["Manufacturer"] +  ", "+ obj["Name"]+", " + obj["Architecture"] + " " + Math.Round(Convert.ToDouble(obj["MaxClockSpeed"]) / 1000, 2) + " GHz, "+
                        "Ядра "+obj["NumberOfCores"]+", Потоки"+ obj["NumberOfLogicalProcessors"]+"; \n";
                }
                Lst.Add(elementInfo);
            }
            catch (Exception ex)
            {
                elementInfo = new ElementInfo();
                elementInfo.TypeName = "Ошибка";
                elementInfo.ModelName = ($"Ошибка при получении информации о процессоре: {ex.Message}");
                Lst.Add(elementInfo);
            }
            return Lst;

        }

        static List<ElementInfo> GetMemoryInfo(List<ElementInfo> Lst)
        {
            ElementInfo elementInfo = null;
            try
            {
                ManagementObjectSearcher searcher = new ManagementObjectSearcher("root\\CIMV2", "SELECT * FROM Win32_PhysicalMemory");
                double totalMemoryGB = 0;
                int stickCount = 0;
                elementInfo = new ElementInfo();
                elementInfo.isSystemInfo = true;
                foreach (ManagementObject obj in searcher.Get())
                {
                    elementInfo.TypeName = "ОЗУ";
                    elementInfo.FullDescription = elementInfo.FullDescription + obj["Manufacturer"] + ", " + obj["PartNumber"];

                    double capacityGB = Convert.ToDouble(obj["Capacity"]) / 1024 / 1024 / 1024;
                    totalMemoryGB += capacityGB;
                    stickCount++;
                    elementInfo.FullDescription = elementInfo.FullDescription + $"Планка { stickCount}: { capacityGB} GB"+
                        obj["Manufacturer"]+", "+obj["PartNumber"]+"\n";                    
                }
                elementInfo.ModelName = $"{totalMemoryGB} GB";
                Lst.Add(elementInfo);
                //Console.WriteLine($"\nВсего оперативной памяти: {totalMemoryGB} GB ({stickCount} планок)");
            }
            catch (Exception ex)
            {
                elementInfo = new ElementInfo();
                elementInfo.TypeName = "Ошибка";
                elementInfo.ModelName = ($"Ошибка при получении информации о памяти: {ex.Message}");
                Lst.Add(elementInfo);
            }
            return Lst;
        }

        static List<ElementInfo> GetDiskInfo(List<ElementInfo> Lst)
        {
            ElementInfo elementInfo = null;
            try
            {
                ManagementObjectSearcher searcher = new ManagementObjectSearcher("root\\CIMV2", "SELECT * FROM Win32_DiskDrive");
                elementInfo = new ElementInfo();
                elementInfo.isSystemInfo = true;
                elementInfo.TypeName = "Диски";

                double totalMemoryGB = 0;
                int HDDCount = 0;
                foreach (ManagementObject obj in searcher.Get())
                {
                    double sizeGB = Math.Round(Convert.ToDouble(obj["Size"]) / 1024 / 1024 / 1024, 2);
                    totalMemoryGB += sizeGB;
                    HDDCount++;
                    elementInfo.FullDescription = elementInfo.FullDescription + obj["Model"] + ", " + obj["InterfaceType"] + $", {sizeGB}  Gb, S/N " + obj["SerialNumber"] + "\n";
                    /*Console.WriteLine($"Диск: {obj["Model"]}");
                    Console.WriteLine($"Тип: {obj["InterfaceType"]}");
                    Console.WriteLine($"Размер: {sizeGB} GB");
                    Console.WriteLine($"Серийный номер: {obj["SerialNumber"]}");
                    Console.WriteLine();*/
                }
                elementInfo.ModelName = $"{totalMemoryGB} GB";
                Lst.Add(elementInfo);
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Ошибка при получении информации о дисках: {ex.Message}");
            }
            return Lst;
        }

        static List<ElementInfo> GetGpuInfo(List<ElementInfo> Lst)
        {
            ElementInfo elementInfo = null;
            //Console.WriteLine("--- Видеокарта ---");
            try
            {
                ManagementObjectSearcher searcher = new ManagementObjectSearcher("root\\CIMV2", "SELECT * FROM Win32_VideoController");

                elementInfo = new ElementInfo();
                elementInfo.isSystemInfo = true;
                foreach (ManagementObject obj in searcher.Get())
                {
                    elementInfo.TypeName = "Видео";
                    elementInfo.ModelName = obj["Name"] + " " + obj["AdapterCompatibility"];
                    elementInfo.FullDescription = elementInfo.FullDescription + obj["AdapterCompatibility"] + ", " + obj["Name"] + ", " + Math.Round(Convert.ToDouble(obj["AdapterRAM"]) / 1024 / 1024 / 1024, 2)+" GB, "+
                        $"Разрешение: {obj["CurrentHorizontalResolution"]}x{obj["CurrentVerticalResolution"]}; \n";
                }
                Lst.Add(elementInfo);


                //foreach (ManagementObject obj in searcher.Get())
                //{
                //    Console.WriteLine($"Модель: {obj["Name"]}");
                //    Console.WriteLine($"Производитель: {obj["AdapterCompatibility"]}");
                //    Console.WriteLine($"Видеопамять: {Math.Round(Convert.ToDouble(obj["AdapterRAM"]) / 1024 / 1024 / 1024, 2)} GB");
                //    Console.WriteLine($"Разрешение: {obj["CurrentHorizontalResolution"]}x{obj["CurrentVerticalResolution"]}");
                //    Console.WriteLine();
                //}
            }
            catch (Exception ex)
            {
                elementInfo = new ElementInfo();
                elementInfo.TypeName = "Ошибка";
                elementInfo.ModelName = ($"Ошибка при получении информации о видеокарте: {ex.Message}");
                Lst.Add(elementInfo);
            }
            return Lst;
        }
    

        static List<ElementInfo> GetNetworkInfo(List<ElementInfo> Lst)
        {
            ElementInfo elementInfo = null;
            try
             {
                elementInfo = new ElementInfo();
                elementInfo.isSystemInfo = true;
                // IP адреса
                string hostName = Dns.GetHostName();
                IPAddress[] addresses = Dns.GetHostAddresses(hostName);

                //Console.WriteLine("IP адреса:");
                foreach (IPAddress ip in addresses)
                {                   
                   if (ip.AddressFamily == System.Net.Sockets.AddressFamily.InterNetwork)
                   {
                       elementInfo.ModelName = $"IP: {ip}"; 
                   }
                }
                elementInfo.FullDescription = elementInfo.ModelName;
               // Адаптеры
               ManagementObjectSearcher searcher = new ManagementObjectSearcher("root\\CIMV2", "SELECT * FROM Win32_NetworkAdapterConfiguration WHERE IPEnabled = 'TRUE'");
                foreach (ManagementObject obj in searcher.Get())
                {
                   elementInfo.TypeName = "Сеть";
                   //elementInfo.ModelName = obj["Name"] + " " + obj["AdapterCompatibility"];
                   elementInfo.FullDescription = elementInfo.FullDescription + obj["Description"] + ", " + obj["MACAddress"]+"; \n";
                   //Console.WriteLine($"\nСетевой адаптер: {obj["Description"]}");
                   // Console.WriteLine($"MAC адрес: {obj["MACAddress"]}");
                }
                Lst.Add(elementInfo);
            }
             catch (Exception ex)
             {
                elementInfo = new ElementInfo();
                elementInfo.TypeName = "Ошибка";
                elementInfo.ModelName = ($"Ошибка при получении сетевой информации: {ex.Message}");
                Lst.Add(elementInfo);
             }
            return Lst;
        }

        static List<ElementInfo> GetOsInfo(List<ElementInfo> Lst)
        {
            ElementInfo elementInfo = null;
            //Console.WriteLine("--- Операционная система ---");
            try
            {
                elementInfo = new ElementInfo();
                elementInfo.isSystemInfo = true;
                ManagementObjectSearcher searcher = new ManagementObjectSearcher("root\\CIMV2", "SELECT * FROM Win32_OperatingSystem");
                foreach (ManagementObject obj in searcher.Get())
                {
                    elementInfo.TypeName = "ОС";
                    elementInfo.ModelName = obj["Caption"]+", "+ obj["Version"];
                    elementInfo.FullDescription = elementInfo.FullDescription + $"ОС: {obj["Caption"]} Версия: {obj["Version"]} Архитектура: {obj["OSArchitecture"]} " +
                            $"Дата установки: {ManagementDateTimeConverter.ToDateTime(obj["InstallDate"].ToString()).ToShortDateString()} Последняя загрузка: {ManagementDateTimeConverter.ToDateTime(obj["LastBootUpTime"].ToString())}";
                     //Console.WriteLine($"Версия: {obj["Version"]}");
                     //Console.WriteLine($"Архитектура: {obj["OSArchitecture"]}");
                     //Console.WriteLine($"Дата установки: {ManagementDateTimeConverter.ToDateTime(obj["InstallDate"].ToString()).ToShortDateString()}");
                     //Console.WriteLine($"Последняя загрузка: {ManagementDateTimeConverter.ToDateTime(obj["LastBootUpTime"].ToString())}");
                }

                 // Дополнительная информация из реестра
                using (RegistryKey key = Registry.LocalMachine.OpenSubKey(@"SOFTWARE\Microsoft\Windows NT\CurrentVersion"))
                {
                     if (key != null)
                     {
                         Console.WriteLine($"Текущая сборка: {key.GetValue("CurrentBuild")}");
                         Console.WriteLine($"Имя продукта: {key.GetValue("ProductName")}");
                     }
                }
                Lst.Add(elementInfo);
            }
             catch (Exception ex)
             {
                elementInfo = new ElementInfo();
                elementInfo.TypeName = "Ошибка";
                elementInfo.ModelName = ($"Ошибка при получении информации об ОС: {ex.Message}");
                Lst.Add(elementInfo);
                 //Console.WriteLine($"Ошибка при получении информации об ОС: {ex.Message}");
             }
            return Lst;
        }
     }
}


