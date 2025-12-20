# 数据标注 Data Label

## 以魔塔社区Qwen2.5-Coder-32B-Instruct为例

Qwen2.5-Coder-32B-Instruct 是一个专为代码生成与编程任务优化的语言模型，其参数量为 320亿（32B）。它基于 Qwen2.5 架构，在代码理解、生成和调试方面表现优异，支持多种编程语言，适用于开发辅助与自动化编程任务。

Demo地址https://www.modelscope.cn/docs/model-service/API-Inference/intro

部署Anaconda虚拟环境，下载完整模型。
```shell
conda create -n ai python==3.11    # 创建虚拟环境
conda activate ai                  # 激活虚拟环境
pip install modelscope                     # 安装modelscope
pip install torch torchvision torchaudio   # 安装PyTorch
pip install openai     # 当前魔搭平台的API-Inference，针对大语言模型提供OpenAI API兼容的接口。 对于LLM模型的API，使用前，请先安装OpenAI SDK
pip install anthropic  # 针对LLM模型，API-Inference也支持与Anthropic API兼容的调用方式。要使用Anthropic模式，请在使用前，安装Anthropic SDK
```

## 以阿里云百炼为例

```shell
conda activate ai                  # 激活虚拟环境
pip install dashscope              # 安装dashscope
```

# 理论题
1. 智慧物流中，仓储机器人实现自动避障和路径规划，主要运用了什么技术? B
    A. 自然语言处理
    B. 计算机视觉与SLAM(同步定位与地图构建)技术
    C. 语音合成
    D. 情感计算

2. 在智慧医疗领域，AI辅助诊断系统主要通过分析什么来辅助医生?  B
    A. 医院的财务报表
    B. 医学影像(如CT、X光)和电子病历
    C. 病人的医保卡余额
    D. 医生的排班表

3. 智能家居系统中，空调自动调节温度主要依赖于哪种传感器数据的分析? C
    A. 摄像头数据
    B. 麦克风数据
    C. 温湿度传感器数据
    D. GPS数据

4. 智能客服机器人通过语义理解用户提问后，会从哪个数据资源中查找最匹配的答案?  C
    A. 语料库
    B. 信息库
    C. 知识库
    D. 关系数据库

5. 基于人工智能技术优化的智能搜索系统，其主要目标是提升哪个方面的效果?  D
    A. 搜索范围
    B. 搜索时长
    C. 搜索结果数量
    D. 搜索准确性

6. 中国提出的汽车“新四化”战略方向中，哪一项是智能化汽车得以发展的基础?  B
    A. 共享化
    B. 网联化
    C. 电动化
    D. 自动化

7. 智能客服机器人是哪种技术或应用场景的典型实例?  B
    A. 模式识别
    B. 人机交互
    C. 人脸识别
    D. 数据挖掘

8. 在对话系统中，多轮对话与单轮对话的主要区别在于?  B
    A. 是否使用了语音技术
    B. 是否需要结合上下文语境进行理解
    C. 回答速度的快慢
    D. 涉及的知识领域不同

9. 音频标注中，WAV格式相比MP3格式的主要优势是?  B
    A. 文件体积更小
    B. 通常为无损格式，音质保留更好
    C. 兼容性更差
    D. 能在不同操作系统中进行播放

10. 对于双声道录音数据的处理，通常需要注意?  A
    A. 双声道音频中各声道内容独立(如对话场景中的不同说话人)，需对每个声道单独标注
    B. 直接合并成单声道即可
    C. 删除右声道
    D. 只需要听左声道

11. 根据连续语音转写的数据处理规范，如果说话人发音停顿时间过长，且一字一顿，该语音段通常被判定为:  A
    A. 长停语音
    B. 无用语音
    C. 可疑语音
    D. 无效语音